<?php
namespace app\controllers;
use app\models\ResenaIgra;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ResenaIgraController extends \yii\web\Controller
{
    
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
                       'roles' => ['@']
                   ]
               ]
           ]
        ];
    }
    
    public function actionKreiranje($korisnik, $igra){
        $model = new ResenaIgra();
        $model->igra_id = $igra;
        $model->korisnik_id = $korisnik;
        return $model->save();
    }
    
}