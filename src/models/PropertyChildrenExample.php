<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 11.09.2018
 * Time: 9:32
 */

namespace yozh\property\models;

use Yii;
use yozh\property\models\Property;
use yozh\form\traits\AttributeActionListTrait;

final class PropertyChildrenExample extends Property
{
	use AttributeActionListTrait {
		AttributeActionListTrait::attributesDefaultList as attributesDefaultListTrait;
	}
	
	public static function tableName()
	{
		return '{{%yozh_property_children_example}}';
	}
	
	public function rules( $rules = [], $update = false )
	{
		static $_rules;
		
		if( !$_rules || $update ) {
			
			$_rules = parent::rules( \yozh\base\components\validators\Validator::merge( [
			
			], $rules ) );
			
		}
		
		return $_rules;
		
	}
	
	public function attributesEditList( ?array $only = null, ?array $except = null, ?bool $schemaOnly = false )
	{
		$attributes = $this->attributesDefaultList( $only, $except, $schemaOnly );
		
		$attributes['value'] = 'value';
		
		return $attributes;
		
	}
	
	public function attributesDefaultList( ?array $only = null, ?array $except = null, ?bool $schemaOnly = false )
	{
		$except = $except ?? [];
		
		$except = array_unique( array_merge( $except, [
			'config',
			'validators',
			//'data', // for SCHEME_TYPE_COMMON_DATA_FIELD
		] ) );
		
		$attributes = self::attributesDefaultListTrait( $only, $except, $schemaOnly );
		
		return $attributes;
	}
	
	
	public function attributesIndexList( ?array $only = null, ?array $except = null, ?bool $schemaOnly = false )
	{
		return $this->attributesDefaultList( $only ?? [
				'name',
				'type',
			], $except, $schemaOnly );
	}
	
	
}