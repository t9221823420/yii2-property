<?php

namespace yozh\property\models;

use yozh\base\models\BaseActiveRecord as ActiveRecord;
use yozh\property\traits\PropertyTrait;

class Property extends ActiveRecord
{
	use PropertyTrait;
	
	const CHILDREN_TYPE_FIELDS_BY_TYPE  = 'fields by type';
	const CHILDREN_TYPE_ONLY_DATA_FIELD = 'only data field';
}
