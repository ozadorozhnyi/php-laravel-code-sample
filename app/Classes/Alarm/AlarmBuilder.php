<?php

namespace App\Classes\Alarm;

use App\Models\Alarm\Alarm;

/**
 * Class AlarmBuilder
 * @package App\Classes\Alarm
 */
class AlarmBuilder implements AlarmBuilderInterface
{
    protected ?Alarm $alarm = null;

    /**
     * AlarmBuilder constructor.
     *
     * @param Alarm|null $alarm
     */
    public function __construct(?Alarm $alarm = null)
    {
        if ($alarm) {
            $this->setAlarm($alarm);
        } else {
            $this->reset();
        }
    }

    /**
     * @param Alarm|null $alarm
     * @return $this
     */
    public function setAlarm(?Alarm $alarm = null)
    {
        $this->alarm = $alarm;
        return $this;
    }

    /**
     * @return AlarmBuilderInterface
     */
    public function reset(): AlarmBuilderInterface
    {
        $this->alarm = new Alarm();
        return $this;
    }

    /**
     * @return Alarm
     */
    public function getAlarm(): Alarm
    {
        $alarm = $this->alarm;
        $this->reset();

        return $alarm;
    }

    /**
     * Set with random token.
     *
     * @param string $token
     * @return AlarmBuilderInterface
     */
    public function setToken(string $token): AlarmBuilderInterface
    {
        $this->alarm->token = $token;
        return $this;
    }

    /**
     * Set User.
     *
     * @param int $user_id
     * @return AlarmBuilderInterface
     */
    public function setUserId(int $user_id): AlarmBuilderInterface
    {
        $this->alarm->user_id = $user_id;
        return $this;
    }

    /**
     * Set Protected Object.
     *
     * @param int $protected_object_id
     * @return AlarmBuilderInterface
     */
    public function setProtectedObjectId(int $protected_object_id): AlarmBuilderInterface
    {
        $this->alarm->protected_object_id = $protected_object_id;
        return $this;
    }

    /**
     * Set Alarm Type.
     *
     * @param int $alarm_type_id
     * @return AlarmBuilderInterface
     */
    public function setAlarmTypeId(int $alarm_type_id): AlarmBuilderInterface
    {
        $this->alarm->alarm_type_id = $alarm_type_id;
        return $this;
    }

    /**
     * Set Alarm Status.
     *
     * @param int $alarm_status_id
     * @return AlarmBuilderInterface
     */
    public function setAlarmStatusId(int $alarm_status_id): AlarmBuilderInterface
    {
        $this->alarm->alarm_status_id = $alarm_status_id;
        return $this;
    }
}
