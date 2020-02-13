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
    
    public function actionIzbrisi($id){
        $igra = Igra::findOne(preg_grep('/[0-9]+/', [$id])); //zbog sigurnosti, uzmi samo brojeve
        if(isset($igra)){
            $igra->delete();
        }
        return $this->redirect(['index']);
        
    }
        
    public function actionNeodobreno($id){
        $igra = Igra::findOne(preg_grep('/[0-9]+/', [$id]));
        $igra->aktivna = -1;
        $igra->save();
        return $this->redirect(['index']);
    }
    
    public function actionAsocijacijeIgre($igraId){
        $asocijacije = (new \app\models\Asocijacija)->vratiSveAsocijacijeIgre($igraId);
        return $this->render('asocijacijaIgre',['asocijacije' => $asocijacije]);
    }
    
    public function actionOdobravanjeAsocijacija(){
        
        $postData = \yii::$app->request->post();
        if(isset($postData['igra']) && isset($postData['odobreno'])){
            $igra = Igra::vratiIgru($postData['igra']);
            $igra->aktivna = 1;
            $igra->save();
        }else if(isset($postData['igra']) && isset($postData['odbijeno'])){
            $igra = Igra::vratiIgru($postData['igra']);
            $igra->aktivna = -1;
            $igra->save();
        }
        $modeliIgara = Igra::vratiNeaktivneIgre(true); //vraca samo jednu od neaktivnih igara
        
        
        return $this->render('kontrolaIgara',['modeliIgara' => $modeliIgara]);
    }
}
