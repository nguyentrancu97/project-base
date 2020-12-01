<?php

namespace Modules\Files\Entities;
use Illuminate\Database\Eloquent\Model;
use App\UTCTimeSupport\HasLocalDates;

/**
 * Class Files
 * @property int $id
 * @property $path
 * @property $path_thumbnail
 * @property $fileable_id
 * @property $fileable_type
 * @package Modules\Files\Entities
 */

class Files extends Model
{
    use HasLocalDates;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'path', 'path_thumbnail', 'fileable_id', 'fileable_type'];

    protected $table = 'files';
}
