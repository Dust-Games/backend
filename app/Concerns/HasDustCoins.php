<?php

namespace App\Concerns;

trait HasDustCoins	
{
	abstract public function getDustCoinsNumColumn();

	public function setDustCoins(float $num)
	{
		$this->setAttribute(
			$this->getDustCoinsNumColumn(),
			$num
		);

		$this->save();
	}

	public function addDustCoins(float $num)
	{
		$this->increment(
			$this->getDustCoinsNumColumn(),
			$num		
		);
	}

	public function reduceDustCoins(float $num)
	{
		$this->decrement(
			$this->getDustCoinsNumColumn(),
			$num		
		);		
	}

	public function getDustCoins()
	{
		return $this->{$this->getDustCoinsNumColumn()};
	}
}