<?php

namespace yozh\property\migrations\namespaced;

require_once dirname(__DIR__) . '/m000000_000000_011_ysell_property_fields_by_type_dev.php';

/**
 * Migration to support namespaced migrations.
 *
 * This migration can be used instead of the one with global namespace.
 *
 * @see https://github.com/yiisoft/yii2/blob/2.0.10/framework/console/controllers/BaseMigrateController.php#L60
 *
 * @author moltam
 */
class m000000_000000_011_ysell_property_fields_by_type_dev extends \m000000_000000_011_ysell_property_fields_by_type_dev
{
}
