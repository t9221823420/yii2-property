<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 21.11.2018
 * Time: 23:18
 */

namespace yozh\property\traits;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
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
		
		$attributes = parent::attributes();
		
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
			
			'required'         => [ [ 'name', 'type', 'widget' ], 'required', ],
			
			//integer
			'integer'          => [ [ 'set', 'weight', ], 'integer' ],
			'integer.positive' => [ [ 'set', ], 'compare', 'skipOnError' => true, 'operator' => '>', 'compareValue' => 0 ],
			
			//string
			'string.max'       => [ [ 'name', ], 'string', 'max' => 255 ],
			'string.trim'      => [ [ 'name', 'value', ], 'filter', 'filter' => 'trim', 'skipOnEmpty' => true, ],
			'string.purifier'  => [ [ 'name', ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process', 'skipOnEmpty' => true, ],
			
			// name contain only word characters
			'name.match' => [ [ 'name', ], 'match', 'pattern' => Html::$attributeRegex,
			                                        'message' => Yii::t( 'app', 'Attribute name must contain word characters only.' ), 'skipOnEmpty' => true ],
			
			'type.in'     => [ [ 'type' ], 'in', 'range' => ActiveField::getConstants( 'INPUT_TYPE' ) ],
			'widget.in'   => [ [ 'widget' ], 'in', 'range' => ActiveField::getConstants( 'WIDGET_TYPE' ) ],
			
			// fks
			'fks.integer' => [ [ 'parent', ], 'integer' ],
			'fks.compare' => [ [ 'parent', ], 'compare', 'skipOnError' => true, 'operator' => '>', 'compareValue' => 0 ],
			
			'fks.exist.parent' => [ [ 'parent' ],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => static::class,
				'targetAttribute' => [ 'parent' => 'id' ],
			],
		
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
	
	public function afterSave( $insert, $changedAttributes )
	{
		parent::afterSave( $insert, $changedAttributes );
		
		$this->setValue( $this->_value );
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