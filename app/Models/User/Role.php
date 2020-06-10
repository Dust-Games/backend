<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['commission'];

    protected $table = 'role';

    protected $guarded = [];

    public $timestamps = false;
}
