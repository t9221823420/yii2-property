<?php

namespace yozh\property;

class Module extends \yozh\base\Module
{

	const MODULE_ID = 'property';
	
	public $controllerNamespace = 'yozh\\' . self::MODULE_ID . '\controllers';
	
}
