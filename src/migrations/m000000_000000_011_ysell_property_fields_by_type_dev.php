<?php

/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 13.09.2018
 * Time: 9:25
 */

use yozh\base\components\db\Migration;
use yozh\base\components\db\Schema;
use yozh\base\components\helpers\ArrayHelper;
use yozh\form\ActiveField;

class m000000_000000_011_ysell_property_fields_by_type_dev extends \yozh\property\migrations\namespaced\m000000_000000_010_ysell_property_dev
{
	
	public function getColumns( $columns = [] )
	{
		return parent::getColumns( ArrayHelper::merge( [
			
			ActiveField::INPUT_TYPE_STRING => $this->string()->null(),
			ActiveField::INPUT_TYPE_TEXT   => $this->text()->null(),
			
			ActiveField::INPUT_TYPE_INTEGER => $this->integer()->null(),
			ActiveField::INPUT_TYPE_DECIMAL => $this->double()->null(),
			
			ActiveField::INPUT_TYPE_DATE     => $this->date()->null(),
			ActiveField::INPUT_TYPE_TIME     => $this->time()->null(),
			ActiveField::INPUT_TYPE_DATETIME => $this->dateTime()->null(),
			
			ActiveField::INPUT_TYPE_BOOLEAN => $this->boolean()->null(),
			ActiveField::INPUT_TYPE_LIST    => $this->json()->null(),
			ActiveField::INPUT_TYPE_HASH    => $this->string( 512 )->null(),
			ActiveField::INPUT_TYPE_JSON    => $this->json()->null(),
		
		], $columns ) );
		
	}
	
	public function getReferences( $references = [] )
	{
		return ArrayHelper::merge( [
			
			/*
			[
				'refTable'   => Model::getRawTableName(),
				'refColumns' => 'id',
				'columns'    => 'parent_id',
				//'onDelete'   => self::CONSTRAINTS_ACTION_RESTRICT,
			],
			*/
		
		], $references );
	}
	
}