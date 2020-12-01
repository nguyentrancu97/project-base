<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class UTCTime extends Model
{
    protected $fillable = ['name' ,'value'];

    protected $table = "utc_time_offsets";
}
