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

class m000000_000000_012_ysell_property_only_data_field_dev extends \yozh\property\migrations\namespaced\m000000_000000_010_ysell_property_dev
{
	
	public function getColumns( $columns = [] )
	{
		return parent::getColumns( ArrayHelper::merge( [
			
			'data' => $this->json()->null(),
		
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