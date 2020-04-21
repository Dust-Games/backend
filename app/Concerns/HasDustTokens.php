<?php

namespace App\Concerns;

trait HasDustTokens	
{
	abstract public function getDustTokensNumColumnName();

	public function setTokens(int $num)
	{
		$this->setAttribute(
			$this->getDustTokensNumColumnName(),
			$num
		);

		$this->save();
	}

	public function addTokens(int $num)
	{
		$this->increment(
			$this->getDustTokensNumColumnName(),
			$num			
		);
	}

	public function reduceTokens(int $num)
	{
		$this->decrement(
			$this->getDustTokensNumColumnName(),
			$num			
		);		
	}
}