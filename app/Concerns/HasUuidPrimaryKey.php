<?php

namespace App\Concerns;

use Ramsey\Uuid\Uuid;

trait HasUuidPrimaryKey
{
	protected static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			$model->setAttribute($model->getKeyName(), (string) Uuid::uuid4());
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
