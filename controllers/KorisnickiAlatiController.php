<?php
namespace app\controllers;
use app\models\Polje;
use \app\models\SablonIgre;
use app\models\posebni_modeli\kreiranjeAsocijacije;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

 * @var $kreiranjeAsocijacije \app\models\posebniModeli\kreiranjeAsocijacije */

class KorisnickiAlatiController extends \yii\web\Controller
{
    public function actionKreiranjeigre(){
        $igra = new \app\models\Igra();
        $kategorija = new \app\models\Kategorija();
        
        if(\yii::$app->request->post('Igra')){
            $igra->attributes = \yii::$app->request->post('Igra'); 
            //probaj kreirati igru u bazi i ako ne uspe, ostani na stranici, u suprotnom idi na intefejz za kreiranje 
            //asocijacija
            return $igra->stvoriIgruUBazi(\yii::$app->user->id) ? $this->redirect(\yii\helpers\Url::to(['kreiranjeasocijacije'
                    , 'trenIgra' => $igra->getAttribute('id'), 'nova' => true])) 
                    : $this->render('kreiranjeIgre', ['igra' => $igra ,'kategorije' 
                    => $kategorija->vratiKategorijeNiz()]);
        }
        
        return $this->render('kreiranjeIgre', ['igra' => $igra ,'kategorije' => $kategorija->vratiKategorijeNiz()]); 
    }
    
    public function actionKreiranjeasocijacije($trenIgra = false, $nova = false){
        if($nova && $trenIgra > -1){
            return $this->redirect(\yii\helpers\Url::to(['kreiranjeasocijacije' , 'trenIgra' => $trenIgra]));
        }
        if($trenIgra === false){
            return $this->redirect(\yii\helpers\Url::to(['kreiranjeigre']));
        }
        $sablon = (new SablonIgre())->vratiSablon(2);
        $nizPolja = (new Polje())->vratiSvaPoljaSablona($sablon->id);
        $pojam = new \app\models\Pojam();
        $igra = (new \app\models\Igra())->vratiIgru($trenIgra)->getModels()[0];
        
        $kreiranjeAsocijacije = new kreiranjeAsocijacije();
        $kreiranjeAsocijacije->igra = $igra;
        $kreiranjeAsocijacije->asocijacija = (new \app\models\Asocijacija());
        $rezultatUpisaUBazu = '';
        if(\yii::$app->request->post('Polje',false)){
            $kreiranjeAsocijacije->sadrzajPoljaNiz = \yii::$app->request->post('Polje')['naziv'];
            $rezultatUpisaUBazu = $kreiranjeAsocijacije
                    ->stvoriAsocijacijuUBazi(\yii::$app->user->id);
        }
        
        return $this->render('kreiranjeAsocijacije', ['polja' => $nizPolja,
            'sablon' => $sablon, 'pojam' => $pojam, 'uspesnost' => $rezultatUpisaUBazu, 'nova' => $nova]);
    }
}