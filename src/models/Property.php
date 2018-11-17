<?php

namespace yozh\property\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yozh\base\models\BaseActiveRecord as ActiveRecord;
use yozh\form\ActiveField;
use yozh\widget\widgets\BaseWidget as Widget;

class Property extends ActiveRecord
{
	const CHILDREN_TYPE_FIELDS_BY_TYPE  = 'fields by type';
	const CHILDREN_TYPE_ONLY_DATA_FIELD = 'only data field';
	
	protected $_value;
	
	protected $_fieldsType;
	
	public function __construct( array $config = [] )
	{
		
		parent::__construct( $config );
		
		$attributes = parent::attributes();
		
		if( array_intersect( $attributes, ActiveField::getConstants( 'INPUT_TYPE' ) ) ) {
			$this->_fieldsType = self::CHILDREN_TYPE_FIELDS_BY_TYPE;
		}
		else if( array_intersect( $attributes, [ 'data' ] ) ) {
			$this->_fieldsType = self::CHILDREN_TYPE_ONLY_DATA_FIELD;
		}
		
	}
	
	public static function tableName()
	{
		return '{{%yozh_property}}';
	}
	
	/*
	static public function getInputsLabels()
	{
		return [
			ActiveField::INPUT_TYPE_STRING   => Yii::t( 'properties', 'String' ),
			ActiveField::INPUT_TYPE_TEXT     => Yii::t( 'properties', 'Text' ),
			ActiveField::INPUT_TYPE_INTEGER  => Yii::t( 'properties', 'Integer' ),
			ActiveField::INPUT_TYPE_DECIMAL  => Yii::t( 'properties', 'Decimal' ),
			ActiveField::INPUT_TYPE_DATE     => Yii::t( 'properties', 'Date' ),
			ActiveField::INPUT_TYPE_TIME     => Yii::t( 'properties', 'Time' ),
			ActiveField::INPUT_TYPE_DATETIME => Yii::t( 'properties', 'Datetime' ),
			ActiveField::INPUT_TYPE_BOOLEAN  => Yii::t( 'properties', 'Boolean' ),
			ActiveField::INPUT_TYPE_LIST     => Yii::t( 'properties', 'List' ),
		
		];
	}
	*/
	
	public function rules()
	{
		return array_merge( parent::rules(), [
			
			'required' => [ [ 'name', 'type', 'widget' ], 'required', ],
			
			'string'   => [ [ 'name', ], 'string', 'max' => 255 ],
			'trim'     => [ [ 'name', 'value', ], 'filter', 'filter' => 'trim' ],
			'purifier' => [ [ 'name', ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process' ],
			
			[ [ 'name', ], 'match', 'pattern' => Html::$attributeRegex,
			                        'message' => Yii::t( 'app', 'Attribute name must contain word characters only.' ) ],
			
			[ [ 'type' ], 'in', 'range' => ActiveField::getConstants( 'INPUT_TYPE' ) ],
			[ [ 'widget' ], 'in', 'range' => ActiveField::getConstants( 'WIDGET_TYPE' ) ],
		
		] );
	}
	
	public function attributes( ?array $only = null, ?array $except = null, ?bool $schemaOnly = false )
	{
		// hide (remove) unnecessary fields
		$except = array_merge( $except ?? []
			, ActiveField::getConstants( 'INPUT_TYPE', true )
		//, [ 'data', ]
		);
		
		// return used field type
		if( $type = $this->getAttribute( 'type' ) ) {
			
			switch( $this->_fieldsType ) {
				
				case self::CHILDREN_TYPE_FIELDS_BY_TYPE:
					
					unset( $except[ $type ] );
					
					break;
				
			}
			
		}
		
		$attributes = parent::attributes( $only, $except, $schemaOnly );
		
		return $attributes;
	}
	
	public function afterFind()
	{
		parent::afterFind();
		
		switch( $this->_fieldsType ) {
			
			case self::CHILDREN_TYPE_FIELDS_BY_TYPE:
				
				$this->_value = $this->getAttribute( $this->type );
				
				break;
			
			case self::CHILDREN_TYPE_ONLY_DATA_FIELD:
				
				$this->_value = $this->getAttribute( 'data' );
				
				break;
			
		}
		
	}
	
	public function afterSave( $insert, $changedAttributes )
	{
		parent::afterSave( $insert, $changedAttributes );
		
		$this->setValue( $this->_value );
	}
	
	public function setValue( $value )
	{
		$this->_value = $value;
		
		if( $type = $this->getAttribute( 'type' ) ) {
			
			switch( $this->_fieldsType ) {
				
				case self::CHILDREN_TYPE_FIELDS_BY_TYPE:
					
					$this->setAttribute( $this->type, $this->_value );
					
					break;
				
				case self::CHILDREN_TYPE_ONLY_DATA_FIELD:
					
					$this->setAttribute( 'data', $this->_value );
					
					break;
				
			}
			
		}
		
	}
	
	public function setAttribute( $name, $value )
	{
		
		switch( $name ) {
			
			case 'value' :
				
				$this->setValue( $value );
				
				break;
			
			default:
				$result = parent::setAttribute( $name, $value );
		}
		
		return $this;
		
	}
	
	public function __get( $name )
	{
		switch( $name ) {
			
			case 'value' :
				
				return $this->_value;
			
			default:
				return parent::__get( $name );
		}
	}
	
	public function __set( $name, $value )
	{
		switch( $name ) {
			
			case 'value' :
				
				$this->setValue( $value );
				
				break;
			
			default:
				return parent::__set( $name, $value );
		}
	}
	
}
