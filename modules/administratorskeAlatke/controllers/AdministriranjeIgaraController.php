<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\administratorskeAlatke\controllers;
use app\models\Igra;
/**
 * Description of AdministriranjaIgaraController
 *
 * @author strahinja
 */
class AdministriranjeIgaraController extends \yii\web\Controller{
    public function actionIndex(){
        $igra = new Igra;
        return $this->render('index',['modelIgra' => $igra->trazi()]);
    }
    
    public function  actionPregledaj($id){
        $igra = Igra::findOne($id);
        
        if(\yii::$app->request->getIsAjax()){
            return $this->renderPartial('pregledaj',['igra' => $igra]);
        }
        return $this->render('pregledaj', ['igra' => $igra]);
    }
}
