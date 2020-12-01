<?php

namespace Modules\Users\Entities;

use App\UTCTimeSupport\HasLocalDates;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Courses\Entities\Course;
use Modules\schedules\Entities\Date;
use Modules\Schedules\Entities\Schedule;
use Modules\Schedules\ScheduleSupport\HasSchedulesTrait;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\schedules\Transformers\DateTransformer;

/**
 * Class User
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $time_id
 * @property string $avatar
 * @package Modules\Users\Entities
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;
    use SoftDeletes;
    use HasLocalDates;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'email', 'password', 'time_id', 'avatar', 'token_device'
    ];
    protected $guard_name = 'admin';

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function UTCTime()
    {
        return $this->belongsTo(UTCTime::class, 'time_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses', 'user_id', 'course_id');
    }

    /**
     * @return BelongsToMany
     */
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_users', 'user_id', 'schedule_id');
    }
}
