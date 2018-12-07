<?php

namespace yozh\property\models;

use yozh\base\components\validators\Validator;
use yozh\base\interfaces\models\ActiveRecordInterface;
use yozh\form\models\BaseActiveRecord;
use yozh\crud\models\BaseActiveRecord as ActiveRecord;
use yozh\form\interfaces\AttributeActionListInterface;
use yozh\form\traits\AttributeActionListTrait;
use yozh\form\traits\DefaultFiltersTrait;

abstract class PropertyCRUD extends Property implements AttributeActionListInterface
{
	use AttributeActionListTrait, DefaultFiltersTrait;
	
	public function attributesIndexList( ?array $only = null, ?array $except = null, ?bool $schemaOnly = false )
	{
		$only = array_unique( array_merge( $only ?? [], [
			'name',
			'label',
			'type',
			'widget',
			'set',
			'weight',
		] ) );
		
		return $this->attributesDefaultList( $only, $except, $schemaOnly );
	}
	
}
