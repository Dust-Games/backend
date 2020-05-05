<?php

namespace App\Concerns;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\QueryException;
use App\Exceptions\NotFoundException;

trait HasUuidPrimaryKey
{
	public static function find($key)
	{
		try {
			$model = (new static)->newQuery()->find($key);

		} catch (QueryException $e) {
			throw new NotFoundException;	
		}

		return $model;	
	}

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
