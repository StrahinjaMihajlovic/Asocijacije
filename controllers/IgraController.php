<?php

namespace app\controllers;
use app\models\Igra;
use app\models\IgraAsocijacija;
use app\models\Polje;
use app\models\SablonIgre;
 use yii\data\ActiveDataProvider;
 use yii\db\ActiveQuery;
class IgraController extends \yii\web\Controller
{
    public function actionIndex(){
        $igra = new Igra();
        $polje = $igra->traziPolja();
        return $this->render('index',['modelPolje' =>  $polje]);
    }
}
