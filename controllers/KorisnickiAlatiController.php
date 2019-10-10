<?php
namespace app\controllers;
use app\models\Polje;
use \app\models\SablonIgre;
use app\models\kreiranjeAsocijacije;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

 * @var $kreiranjeAsocijacije \app\models\posebniModeli\kreiranjeAsocijacije */

class KorisnickiAlatiController extends \yii\web\Controller
{
    public function actionKreiranjeigre($trenIgra = false){
        if($trenIgra === false){
            $trenIgra = (new \app\models\Igra())->stvoriIgruUBazi(\yii::$app->user->id);
            return $trenIgra === false ? $this->redirect('site/index')
                    : $this->redirect(\yii\helpers\Url::to(['kreiranjeigre' , 'trenIgra' => $trenIgra->id]));
            
        }
        $sablon = (new SablonIgre())->vratiSablon(2);
        $nizPolja = (new Polje())->vratiSvaPoljaSablona($sablon->id);
        $pojam = new \app\models\Pojam();
        $igra = (new \app\models\Igra())->vratiIgru($trenIgra)->getModels()[0];
        $kreiranjeAsocijacije = new kreiranjeAsocijacije();
        $kreiranjeAsocijacije->igra = $igra;
        $kreiranjeAsocijacije->asocijacija = (new \app\models\Asocijacija());
        if(\yii::$app->request->post('Polje',false)){
            $kreiranjeAsocijacije->sadrzajPoljaNiz = \yii::$app->request->post('Polje')['naziv'];
            $kreiranjeAsocijacije->stvoriAsocijacijuUBazi(\yii::$app->user->id);
        }
        
        return $this->render('kreiranjeIgre', ['polja' => $nizPolja,
            'sablon' => $sablon, 'pojam' => $pojam]);
    }
}