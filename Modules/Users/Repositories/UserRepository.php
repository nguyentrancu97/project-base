<?php

namespace Modules\Users\Repositories;

use Modules\Users\Entities\User;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepository
 * @package Modules\Users\Repositories
 */

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

    public function getListUser($request){

    }

}
