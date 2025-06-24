<?php 
namespace App\SpotHire\Transformers;

abstract class Transformer
{
	/**
	 * Trasnform a collection of items
	 *
	 * @param $items
	 * @return array
	 **/
	public function transformCollection(array $items)
	{
		return array_map([$this, 'transform'], $items);
	}

	public abstract function transform($item);
}