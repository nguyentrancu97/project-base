<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = ['token', 'email', 'created_at'];

    protected $table = "password_resets";

    public $timestamps = false;

}
