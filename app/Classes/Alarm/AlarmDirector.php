<?php

namespace App\Classes\Alarm;

use App\Classes\Alarm\Exceptions\MismatchedObjectIdException;
use App\Models\Alarm\{Alarm, PanelSignal, PanelSignalCode, Status as AlarmStatus, Status, Type as AlarmType};
use App\Models\ProtectedObject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use \Exception;

/**
 * Class AlarmDirector
 * Keep all functionality around creating and registering a new Alarm.
 *
 * @package App\Classes\Alarm
 */
class AlarmDirector
{
    private $builder;

    /**
     * Default status for the successfully created new alarm.
     *
     * @var int $alarm_status_id_default
     */
    private int $alarm_status_id_default;

    /**
     * ID for the manual alarm type.
     *
     * @var int $alarm_type_id_manual
     */
    private int $alarm_type_id_manual;

    /**
     * ID for the automatic alarm type
     *
     * @var int $alarm_type_id_automatic
     */
    private int $alarm_type_id_automatic;

    /**
     * AlarmDirector constructor.
     * @param AlarmBuilderInterface|null $builder
     */
    public function __construct(?AlarmBuilderInterface $builder = null)
    {
        $this->builder = $builder ?? new AlarmBuilder();

        $this->alarm_status_id_default = AlarmStatus::ofType(AlarmStatus::ACTIVE)->id;

        $this->alarm_type_id_manual = AlarmType::ofType(AlarmType::MANUAL)->id;
        $this->alarm_type_id_automatic = AlarmType::ofType(AlarmType::AUTOMATIC)->id;
    }

    public function cancel(string $token): void
    {
        $active_alarm = user()->alarms()->latest()->active()->first();

        if (!$active_alarm) {
            throw new Exception(
                "There is no active alarms found for the current user logged in."
            );
        }

        if ($token !== $active_alarm->token) {
            throw new Exception(
                "Wrong alarm cancel request."
            );
        }

        $current_status = $active_alarm->alarm_status_id;
        $active_statuses = [$this->alarm_status_id_default];
        if(!in_array($current_status, $active_statuses)) {
            throw new Exception(
                "An alarm with an active status can be canceled."
            );
        }

        ($active_alarm->status()->associate(
            AlarmStatus::ofType(AlarmStatus::CANCELED_BY_CUSTOMER)
        ))->save();
    }

    /**
     * Create a new Alarm, based on the data from mobile app.
     *
     * @param int $protected_object_id
     * @return Alarm
     */
    public function createFromTheMobileApp(int $protected_object_id): Alarm
    {
        return $this->createAlarm(
            user()->id,
            $protected_object_id,
            $this->alarm_type_id_manual,
            $this->alarm_status_id_default
        );
    }

    /**
     * Create a new Alarm instance, based on the Panel Signal.
     *
     * @param int $object_id
     * @param string $event_type
     * @param int $event_code
     * @return Alarm|null
     * @throws MismatchedObjectIdException
     */
    public function createFromPanelSignal(int $object_id, string $event_type, int $event_code): ?Alarm
    {
        $panel_signal = PanelSignal::create([
            'object_id' => $object_id,
            'event_type' => $event_type,
            'event_code' => $event_code
        ]);

        $protected_object = ProtectedObject::where('uos_object_id', $object_id)->first();
        if (!$protected_object) {
            // Failed to identify the protected object on which the alarm occurred.
            throw new MismatchedObjectIdException(
                "Failed to find the object based on the identifier that comes from the security console"
            );
        }

        $panel_signal_code = PanelSignalCode::where('code', $panel_signal->event_code)->first();
        if ($panel_signal_code) {
            if ($panel_signal_code->trigger_alarm) {
                $alarm = $this->createAlarm(
                    $protected_object->user->id,
                    $protected_object->id,
                    $this->alarm_type_id_automatic,
                    $this->alarm_status_id_default
                );

                // Save the security console code that triggered the alarm.
                $alarm->panelSignals()->attach($panel_signal_code->id);

                return $alarm;
            }
        }

        return null;
    }

    /**
     * Create a new Alarm.
     *
     * @param int $user_id
     * @param int $protected_object_id
     * @param int $alarm_type_id
     * @param int $alarm_status_id
     * @return Alarm
     */
    protected function createAlarm(
        int $user_id,
        int $protected_object_id,
        int $alarm_type_id,
        int $alarm_status_id
    ): Alarm
    {
        $this->builder
            ->setUserId($user_id)
            ->setProtectedObjectId($protected_object_id)
            ->setAlarmTypeId($alarm_type_id)
            ->setToken(Str::random(32));

        // @todo: добавить проверки:
        //  1. у юзера нету долгов
        //  2. у юзера нету активных тревог
        $this->builder->setAlarmStatusId($alarm_status_id);

        return $this->save(
            $this->builder->getAlarm()
        );
    }

    /**
     * Save current instance.
     *
     * @param Alarm $alarm
     * @return Alarm
     */
    protected function save(Alarm $alarm): Alarm
    {
        DB::transaction(function() use ($alarm) {
            $alarm->save();
        });

        return $alarm;
    }
}
