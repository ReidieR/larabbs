<?php

namespace App\Models\Traits;

trait QueryBuilderBindable
{
	public function resolveRouteBinding($value)
	{
		$queryClass = property_exists($this, 'queryClass') ? $this->queryClass
			: '\\app\\Http\\Queries\\' . class_basename(self::class) . 'Query';

		if (!class_exists($queryClass)) {
			return parent::resolveRouteBing($value);
		}

		return (new $queryClass($this))
			->where($this->getRouteKeyName(), $value)
			->first();
	}
}
