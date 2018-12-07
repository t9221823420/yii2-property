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

class m000000_000000_yozh_property_dev extends Migration
{
	protected static $_table;
	
	public function __construct( array $config = [] )
	{
		static::$_table = static::$_table ?? Property::getRawTableName();
		
		parent::__construct( $config );
	}
	
	public function safeUp( $params = [] )
	{
		return parent::safeUp( $params );
	}
	
	public function getColumns( $columns = [] )
	{
		return parent::getColumns( array_merge( [
			
			'name'       => $this->string()->notNull(),
			'label'      => $this->string()->null()->after( 'name' ),
			
			'type'       => $this->enum( ActiveField::getConstants( 'INPUT_TYPE' ) )
			                     ->notNull()->defaultValue( ActiveField::DEFAULT_INPUT_TYPE ),
			'widget'     => $this->enum( ActiveField::getConstants( 'WIDGET_TYPE' ) )
			                     ->notNull()->defaultValue( ActiveField::DEFAULT_WIDGET_TYPE ),
			
			'config'     => $this->json()->null(),
			'validators' => $this->json()->null(),
			
			'set'        => $this->integer()->null(),
			'parent'     => $this->integer()->null(),
			'weight'     => $this->integer()->defaultValue( 0 ),
		
		], $columns ) );
	}
	
	public function getReferences( $references = [] )
	{
		return parent::getReferences( array_merge( [
			
			/*
			[
				'refTable'   => 'parent',
				'refColumns' => 'id',
				'columns'    => 'parent_id',
				//'onDelete'   => self::CONSTRAINTS_ACTION_RESTRICT,
			],
			*/
		
		], $references ) );
	}
	
}