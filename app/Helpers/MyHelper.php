<?php

namespace App\Helpers;
use Carbon\Carbon;
use App\Models\ActivityLogDetail;
use App\Models\LoginLogDetail;

class MyHelper
{
    public static function addActivityLog($user_id, $task_type_id, $desc, $ipAddress) {
        $log = new ActivityLogDetail();
        $log->user_id = $user_id;
        $log->activity_type = 'Save';
        $log->task_type = $task_type_id;
        $log->task_description = $desc;
        $log->ipAddress = $ipAddress;
        $log->created_at = Carbon::now();
        $log->updated_at = Carbon::now();
        $log->save();

    }

    public static function upActivityLog($user_id, $task_type_id, $desc, $ipAddress) {
        $log = new ActivityLogDetail();
        $log->user_id = $user_id;
        $log->activity_type = 'Update';
        $log->task_type = $task_type_id;
        $log->task_description = $desc;
        $log->ipAddress = $ipAddress;
        $log->created_at = Carbon::now();
        $log->updated_at = Carbon::now();
        $log->save();

    }

    public static function addLoginLog($U_id, $ipAddress, $loginTime) {
        $log = new LoginLogDetail();
        $log->userId = $U_id;
        $log->activityType = 'Session IN';
        $log->ipAddress = $ipAddress;
        $log->loginTime = $loginTime;
        $log->created_at = Carbon::now();
        $log->updated_at = Carbon::now();
        $log->save();

    }

    public static function addLogoutLog($U_id, $ipAddress, $loginTime) {
        $log = new LoginLogDetail();
        $log->userId = $U_id;
        $log->activityType = 'Session OUT';
        $log->ipAddress = $ipAddress;
        $log->loginTime = $loginTime;
        $log->created_at = Carbon::now();
        $log->updated_at = Carbon::now();
        $log->save();

    }
}
