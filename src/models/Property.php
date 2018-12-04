<?php

namespace yozh\property\models;

use yozh\base\models\BaseActiveRecord as ActiveRecord;
use yozh\property\traits\PropertyTrait;

abstract class Property extends ActiveRecord
{
	use PropertyTrait;
	
	const SCHEME_TYPE_MASK            = 'mask';
	const SCHEME_TYPE_FIELDS_BY_TYPE  = 'fields by type';
	const SCHEME_TYPE_COMMON_DATA_FIELD = 'common data field';
	
	public static function tableName()
	{
		throw new \yii\base\InvalidParamException( "Table name is not set" );
	}
	
	
}
