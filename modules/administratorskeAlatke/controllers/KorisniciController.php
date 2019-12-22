<?php

namespace app\modules\administratorskeAlatke\controllers;

use app\models\Korisnik;
use yii\web\Controller;

class KorisniciController extends \yii\web\Controller {
    
    public function actionIndex(){
         $korisnici = (new \app\models\Korisnik())->vratiSveKorisnikeSemSebe(\yii::$app->user->getId()); //dataProvider
        return $this->render('index',['dataProvider' => $korisnici]);
    }
    public function actionIzbrisi($id){
        $korisnik = (new Korisnik())->findOne($id);
        isset($korisnik) && $korisnik->delete();
        return $this->redirect(\yii\helpers\Url::to(['index']));
    }
    public function actionPregledaj($id){
        $korisnik = Korisnik::findOne($id);
        
        return \yii::$app->request->isAjax ? $this
                ->renderAjax('pregledaj', ['model' => $korisnik])
                :$this->render('pregledaj', ['model' => $korisnik]);
    }
}

