<?php

namespace App\Classes\Alarm;

use App\Models\Alarm\Alarm;

/**
 * Interface AlarmBuilderInterface
 * @package App\Classes\Alarm
 */
interface AlarmBuilderInterface
{
    public function reset(): AlarmBuilderInterface;

    public function setUserId(int $user_id): AlarmBuilderInterface;

    public function setProtectedObjectId(int $protected_object_id): AlarmBuilderInterface;

    public function setAlarmTypeId(int $alarm_type_id): AlarmBuilderInterface;

    public function setAlarmStatusId(int $alarm_status_id): AlarmBuilderInterface;

    public function setToken(string $token): AlarmBuilderInterface;

    public function getAlarm(): Alarm;
}
