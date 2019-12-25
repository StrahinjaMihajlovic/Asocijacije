<?php

namespace app\modules\administratorskeAlatke\controllers;

use app\models\Korisnik;
use yii\web\Controller;

class KorisniciController extends \yii\web\Controller {
    
    public function actionIndex(){
        
         $dataFilter = new \app\models\KorisnikSearch();
         $korisnici = $dataFilter->searchSemSebe(\yii::$app->user->getId(), \yii::$app->request->get()); //dataProvider
        return $this->render('index',['dataProvider' => $korisnici, 'dataFilter' => $dataFilter]);
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
    public function actionKreiraj(){
        $korisnik = new Korisnik();
        $korisnik->setScenario('kreiranjeKorisnika');
        if($korisnik->load(\yii::$app->request->post()) && $korisnik->validate()){
            $korisnik->novaLozinka($korisnik->lozinka_uneta);
            $korisnik->noviAuthKod();
            $korisnik->noviResetKod();
             return $korisnik->save() ? $this->redirect(['pregledaj', 'id' => $korisnik->id])
                :$this->render('kreiraj',['korisnik'=>$korisnik]);
        }
        
        return $this->render('kreiraj',['korisnik' => $korisnik]);
        
       
    }
}

