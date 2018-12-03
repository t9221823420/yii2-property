<?php

namespace yozh\property\models;

use yozh\crud\models\BaseActiveRecord as ActiveRecord;
use yozh\property\traits\PropertyTrait;

abstract class PropertyCRUD extends Property
{
	public function attributesIndexList( ?array $only = null, ?array $except = null, ?bool $schemaOnly = false )
	{
		$only = array_unique( array_merge( $only ?? [], [
			'name',
			'type',
			'widget',
		] ) );
		
		return $this->attributesDefaultList( $only, $except, $schemaOnly );
	}
}
