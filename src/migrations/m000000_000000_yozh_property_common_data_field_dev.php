<?php

/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 13.09.2018
 * Time: 9:25
 *
 *
 */

use yozh\base\components\db\Migration;
use yozh\base\components\db\Schema;
use yozh\base\components\helpers\ArrayHelper;
use yozh\form\ActiveField;

class m000000_000000_yozh_property_common_data_field_dev extends \yozh\property\migrations\namespaced\m000000_000000_yozh_property_dev
{
	
	public function getColumns( $columns = [] )
	{
		return parent::getColumns( array_merge( [
			
			'data' => $this->json()->null(),
		
		], $columns ) );
		
	}
	
	public function getReferences( $references = [] )
	{
		return parent::getReferences( array_merge( [
			
			/*
			[
				'refTable'   => Model::getRawTableName(),
				'refColumns' => 'id',
				'columns'    => 'parent_id',
				//'onDelete'   => self::CONSTRAINTS_ACTION_RESTRICT,
			],
			*/
		
		], $references ) );
	}
	
}