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

final class _m000000_000000_yozh_property_children_example_dev extends \yozh\property\migrations\namespaced\m000000_000000_yozh_property_dev
{
	
	public function __construct( array $config = [] )
	{
		static::$_table = \yozh\ysell\models\product\ProductTemplateField::getRawTableName();
		
		parent::__construct( $config );
	}
	
	public function getColumns( $columns = [] )
	{
		return parent::getColumns( array_merge( [
			'property_template_id' => $this->integer()->after( 'name' ),
		], $columns ) );
		
	}
	
	public function getReferences( $references = [] )
	{
		return parent::getReferences( array_merge( [
			
			[
				'refTable'   => \yozh\ysell\models\product\ProductTemplate::getRawTableName(),
				'refColumns' => 'id',
				'columns'    => 'property_template_id',
				//'onDelete'   => self::CONSTRAINTS_ACTION_RESTRICT,
			],
		
		], $references ) );
	}
	
}