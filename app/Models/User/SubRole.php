<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SubRole extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
