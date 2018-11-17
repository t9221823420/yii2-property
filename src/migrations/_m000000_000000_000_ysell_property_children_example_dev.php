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

class _m000000_000000_000_ysell_property_children_example_dev extends \yozh\property\migrations\namespaced\m000000_000000_010_ysell_property_dev
{
	
	public function __construct( array $config = [] )
	{
		static::$_table = static::$_table ?? \yozh\ysell\models\product\ProductTemplateField::getRawTableName();
		
		parent::__construct( $config );
	}
	
	public function getColumns( $columns = [] )
	{
		return parent::getColumns( ArrayHelper::merge( [
			'product_template_id' => $this->integer()->after( 'name' ),
		], $columns ) );
		
	}
	
	public function getReferences( $references = [] )
	{
		return ArrayHelper::merge( [
			
			[
				'refTable'   => \yozh\ysell\models\product\ProductTemplate::getRawTableName(),
				'refColumns' => 'id',
				'columns'    => 'product_template_id',
				//'onDelete'   => self::CONSTRAINTS_ACTION_RESTRICT,
			],
		
		], $references );
	}
	
}