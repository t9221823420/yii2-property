<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 21.11.2018
 * Time: 23:18
 */

namespace yozh\property\traits;

use Yii;
use yii\base\ErrorHandler;
use yii\base\Model;
use yii\helpers\Html;
use yozh\base\components\helpers\ArrayHelper;
use yozh\base\components\helpers\Inflector;
use yozh\base\components\validators\Validator;
use yozh\base\interfaces\models\ActiveRecordInterface;
use yozh\base\models\BaseActiveRecord as ActiveRecord;
use yozh\form\ActiveField;
use yozh\property\models\Property;
use yozh\widget\widgets\BaseWidget as Widget;

trait PropertyTrait
{
	protected $_value;
	
	/**
	 * @var
	 * define type of Property realization
	 *
	 */
	protected $_schemeType;
	
	public function __construct( array $config = [] )
	{
		
		parent::__construct( $config );
		
		//$attributes = parent::attributes();
		
		if( !$this->_schemeType ) {
			throw new \yii\base\InvalidParamException( "Property::schemeType have to be set" );
		}
		else if( !in_array( $this->_schemeType, self::getConstants( 'SCHEME_TYPE' ) ) ) {
			throw new \yii\base\InvalidParamException( "Invalid type of Property::schemeType" );
		}
		
		/**
		 * if any of INPUT_TYPE exists in Model it's TYPE_FIELDS_BY_TYPE
		 * else if Model has common 'data' field it's TYPE_COMMON_DATA_FIELD
		 */
		/*
		if( array_intersect( $attributes, ActiveField::getConstants( 'INPUT_TYPE' ) ) ) {
			$this->_schemeType = Property::SCHEME_TYPE_FIELDS_BY_TYPE;
		}
		else if( array_intersect( $attributes, [ 'data' ] ) ) {
			$this->_schemeType = Property::SCHEME_TYPE_COMMON_DATA_FIELD;
		}
		*/
		
	}
	
	/**
	 * PHP magic method that returns the string representation of this object.
	 * @return string the string representation of this object.
	 */
	public function __toString()
	{
		// __toString cannot throw exception
		// use trigger_error to bypass this limitation
		try {
			return $this->render();
		} catch( \Exception $e ) {
			ErrorHandler::convertExceptionToError( $e );
			
			return '';
		}
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
	
	public function rules( $rules = [], $update = false )
	{
		static $_rules;
		
		if( !$_rules || $update ) {
			
			$_rules = [
				
				'required'         => [ [ 'name', 'type', 'widget' ], 'required', ],
				
				//integer
				'integer'          => [ [ 'set', 'weight', ], 'integer' ],
				'compare.positive' => [ [ 'set', ], 'compare', 'skipOnError' => true, 'operator' => '>', 'compareValue' => 0 ],
				
				//string
				'string'           => [ [ 'name', 'label', ], 'string', 'max' => 255 ],
				'filter.trim'      => [ [ 'name', 'label', 'value', ], 'filter', 'filter' => 'trim', 'skipOnEmpty' => true, ],
				'filter.purifier'  => [ [ 'name', 'label', ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process', 'skipOnEmpty' => true, ],
				
				// name contain only word characters
				'match.name'       => [ [ 'name', ], 'match', 'pattern' => Html::$attributeRegex,
				                                              'message' => Yii::t( 'app', 'Attribute name must contain word characters only.' ), 'skipOnEmpty' => true ],
				
				'in.type'   => [ [ 'type' ], 'in', 'range' => ActiveField::getConstants( 'INPUT_TYPE' ) ],
				'in.widget' => [ [ 'widget' ], 'in', 'range' => ActiveField::getConstants( 'WIDGET_TYPE' ) ],
				
				'json'        => [ [ 'validators', 'config', ], function( $attribute ) {
					
					if( is_string($this->$attribute) ){
						
						$result = \yii\helpers\Json::decode( $this->$attribute );
						
						if( json_last_error() ) {
							$this->addError( $attribute, \Yii::t( 'app', "Invalid or malformed data for JSON" ) );
						}
						else{
							$this->$attribute = $result;
						}
					}
					
				} ],
				
				// fks
				'integer.fks' => [ [ 'parent', ], 'integer' ],
				'compare.fks' => [ [ 'parent', ], 'compare', 'skipOnError' => true, 'operator' => '>', 'compareValue' => 0 ],
				
				'exist.fks.parent' => [ [ 'parent' ],
					'exist',
					'skipOnError'     => true,
					'targetClass'     => static::class,
					'targetAttribute' => [ 'parent' => 'id' ],
				],
			
			];
			
			if( $this instanceof ActiveRecordInterface ) {
				$_rules = parent::rules( Validator::merge( $_rules, $rules ) );
			}
			
		}
		
		return $_rules;
		
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
			
			switch( $this->_schemeType ) {
				
				case self::SCHEME_TYPE_FIELDS_BY_TYPE:
					
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
		
		switch( $this->_schemeType ) {
			
			case self::SCHEME_TYPE_FIELDS_BY_TYPE:
				
				$this->_value = $this->getAttribute( $this->type );
				
				break;
			
			case self::SCHEME_TYPE_COMMON_DATA_FIELD:
				
				$this->_value = $this->getAttribute( 'data' );
				
				break;
			
		}
		
	}
	
	public function beforeSave( $insert )
	{
		$this->label = !empty( $this->label ) ? $this->label : Inflector::titleize( $this->name );
		
		return parent::beforeSave( $insert ); // TODO: Change the autogenerated stub
	}
	
	public function afterSave( $insert, $changedAttributes )
	{
		return parent::afterSave( $insert, $changedAttributes );
		
		//$this->setValue( $this->_value );
	}
	
	public function setValue( $value )
	{
		$this->_value = $value;
		
		if( $type = $this->getAttribute( 'type' ) ) {
			
			switch( $this->_schemeType ) {
				
				case self::SCHEME_TYPE_FIELDS_BY_TYPE:
					
					$this->setAttribute( $this->type, $this->_value );
					
					break;
				
				case self::SCHEME_TYPE_COMMON_DATA_FIELD:
					
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