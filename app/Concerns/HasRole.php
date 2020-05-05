<?php

namespace App\Concerns;

trait HasRole
{
	abstract public function getRole();

	public function isAdmin()
	{
		return $this->getRole() === 2;
	}
}