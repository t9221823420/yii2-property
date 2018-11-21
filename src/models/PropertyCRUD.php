<?php

namespace yozh\property\models;

use yozh\crud\models\BaseActiveRecord as ActiveRecord;
use yozh\property\traits\PropertyTrait;

class PropertyCRUD extends ActiveRecord
{
	use PropertyTrait;
	
	const CHILDREN_TYPE_FIELDS_BY_TYPE  = 'fields by type';
	const CHILDREN_TYPE_ONLY_DATA_FIELD = 'only data field';
	
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
