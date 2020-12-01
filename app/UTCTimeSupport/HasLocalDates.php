<?php

namespace App\UTCTimeSupport;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait HasLocalDates {

    /**
     * Localize a date to users timezone
     *
     * @param null $dateField
     * @return Carbon
     */
    public function localize($dateField = null)
    {
        if(Auth::guard('admin')->check()){
            $guard = 'admin';
        } else {
            $guard = 'customer';
        }
        $dateValue = is_null($this->{$dateField}) ? Carbon::now() : $this->{$dateField};
        return $this->inUsersTimezone($dateValue, $guard);
    }

    /**
     * Change timezone of a carbon date
     *
     * @param $dateValue
     * @return Carbon
     */
    private function inUsersTimezone($dateValue, $guard = "admin"): Carbon
    {
        $timezone = optional(auth($guard)->user())->UTCTime->value ?? config('app.timezone');
        return $this->asDateTime($dateValue)->timezone($timezone);
    }
}
