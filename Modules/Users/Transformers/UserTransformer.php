<?php

namespace Modules\Users\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Modules\Courses\Transformers\CoursesTransformer;
use Modules\Users\Entities\User;

class UserTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'timezones', 'roles'
    ];
    /**
     * Include resources without needing it to be requested.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'courses'
    ];


    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar ? asset($user->avatar) : config('app.url').'/user.jpg',
        ];
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function includeRoles(User $user)
    {
        $roles = $user->roles;
        return $this->collection($roles, new RoleTransformer);
    }

    /**
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeTimezones(User $user)
    {
        if($user->UTCTime) {
            $utcTime = $user->UTCTime;
            return $this->item($utcTime, new UTCTimeTransformer);
        }
    }

    public function includeCourses(User $user)
    {
        $course = $user->courses;
        return $this->collection($course, new CoursesTransformer);
    }


}
