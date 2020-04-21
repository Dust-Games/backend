<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Concerns\HasUuidPrimaryKey;

class Transaction extends Model
{
	use HasUuidPrimaryKey;

    protected $table = 'transaction';

    protected $guarded = [];
}
