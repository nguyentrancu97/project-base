<?php

namespace Modules\Files\Repositories;

use Modules\Files\Entities\Files;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class FilesRepository
 * @package Modules\Platform\User\Repositories
 */
class FilesRepository extends BaseRepository
{
    public function model()
    {
        return Files::class;
    }

}