<?php

/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 11.09.2018
 * Time: 9:41
 */

use yozh\base\components\db\Migration;
use yozh\base\components\db\Schema;
use yozh\base\components\helpers\ArrayHelper;
use yozh\form\ActiveField;
use yozh\property\models\Property;

class m000000_000000_010_ysell_property_dev extends Migration
{
	
	public function __construct( array $config = [] )
	{
		
		static::$_table = static::$_table ?? Property::getRawTableName();
		
		parent::__construct( $config );
	}
	
	
	public function safeUp( $params = [] )
	{
		
		parent::safeUp( [
			'mode' => 1 ? static::ALTER_MODE_UPDATE : static::ALTER_MODE_IGNORE,
		] );
		
	}
	
	public function getColumns( $columns = [] )
	{
		
		return parent::getColumns( ArrayHelper::merge( [
			
			'name'       => $this->string()->notNull(),
			'type'       => $this->enum( ActiveField::getConstants( 'INPUT_TYPE' ) )->notNull()->defaultValue( ActiveField::DEFAULT_INPUT_TYPE ),
			'widget'     => $this->enum( ActiveField::getConstants( 'WIDGET_TYPE' ) )->notNull()->defaultValue( ActiveField::DEFAULT_WIDGET_TYPE ),
			'config'     => $this->json()->null(),
			'validators' => $this->json()->null(),
		
		], $columns ) );
	}
	
	public function getReferences( $references = [] )
	{
		return ArrayHelper::merge( [
			
			/*
			[
				'refTable'   => 'parent',
				'refColumns' => 'id',
				'columns'    => 'parent_id',
				//'onDelete'   => self::CONSTRAINTS_ACTION_RESTRICT,
			],
			*/
		
		], $references );
	}
	
}