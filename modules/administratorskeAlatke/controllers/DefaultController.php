<?php

namespace app\modules\administratorskeAlatke\controllers;

use yii\web\Controller;

/**
 * Default controller for the `administratorskeAlatke` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    
    public function behaviors() {
        parent::behaviors();
        return [
           'access' => [
               'class' => \yii\filters\AccessControl::class,
               'rules' => [
                   [
                       'allow' => false,
                       'roles' => ['?']
                   ],
                   [
                       'allow' => true,
                       'roles' => ['admin']
                   ],
                   [
                        'allow' => false,
                       'roles' => ['@']
                   ]
               ]
           ]
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
}
