<?php

namespace App\Concerns;

use Ramsey\Uuid\Uuid;

trait HasUuidPrimaryKey
{
	protected static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			$model->setAttribute($model->getKeyName(), Uuid::uuid4()->toString());
		});
	}

	public function getIncrementing()
	{
		return false;
	}

	public function getKeyType()
	{
		return 'string';
	}
}	
