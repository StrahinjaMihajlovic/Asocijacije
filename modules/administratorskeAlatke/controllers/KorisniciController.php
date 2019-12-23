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
    public function actionIzmeni($id){
        $korisnik = Korisnik::findOne($id);
        $korisnik->setScenario('promenaPodatakaAdmin');
        if(\yii::$app->request->post()){ 
            $korisnik->load(\yii::$app->request->post());
            if(isset($korisnik->lozinka_uneta) && $korisnik->lozinka_uneta !== ''){
                $korisnik->novaLozinka($korisnik->lozinka_uneta);
            }
            $korisnik->update(true, ['korisnicko_ime', 'email', 'aktivan', 'lozinka']);
            return $this->render('pregledaj',['model' => $korisnik]);
        }
        
        return $this->render('izmeni', ['clan' => $korisnik]);
    }
}

