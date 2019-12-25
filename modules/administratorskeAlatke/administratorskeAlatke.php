<?php

namespace app\modules\administratorskeAlatke;

/**
 * administratorskeAlatke module definition class
 */
class administratorskeAlatke extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\administratorskeAlatke\controllers';
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::setAlias('@adminAlatke', "@app/modules/administratorskeAlatke");
        // custom initialization code goes here
    }
}
