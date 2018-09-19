<?php

namespace yozh\property;

class AssetBundle extends \yozh\base\AssetBundle
{

    public $sourcePath = __DIR__ .'/../assets/';

    public $css = [
        //'css/yozh-property.css',
	    //['css/yozh-property.print.css', 'media' => 'print'],
    ];
	
    public $js = [
        //'js/yozh-property.js'
    ];
	
    public $depends = [
        //'yii\bootstrap\BootstrapAsset',
    ];	
	
	public $publishOptions = [
		//'forceCopy'       => true,
	];
	
}