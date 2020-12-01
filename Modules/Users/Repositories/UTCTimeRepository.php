<?php

namespace Modules\Users\Repositories;

use Modules\Users\Entities\UTCTime;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UTCTimeRepository
 * @package Modules\Users\Repositories
 */

class UTCTimeRepository extends BaseRepository
{
    public function model()
    {
        return UTCTime::class;
    }

}
