<?php
use yii\helpers\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*@var $model app\models\Asocijacija */
/*@var $this yii\web\view*/

$pojamModel = new app\models\Pojam();
$modelVezaPojamAsocPolje = $model->vratiVezuPojamAsocijacijaPolje()->all();
foreach($modelVezaPojamAsocPolje as $veza){
    $polje = $veza->polje;
     if($polje->naziv === "Resenje"){
        
        echo Html::tag('div',Html::label($polje->naziv, $polje->naziv) 
                .Html::input('text', $polje->naziv, $veza->pojam->sadrzaj)
                ,['id' => 'Resenje', 'class' => 'tekstPolje polje']);
                       
     }else if(preg_match('/\d/', $polje->naziv)){
        $broj = implode(preg_grep('/\d/', str_split($polje->naziv)));
        $tip = implode(preg_grep('/\d/', str_split($polje->naziv), PREG_GREP_INVERT));
        echo Html::tag('div',Html::label($polje->naziv, $polje->naziv) 
                .Html::input('text', $polje->naziv, $veza->pojam->sadrzaj)
                ,['data-value' => $broj, 'id' => $polje->naziv, 'class' => $tip .' polje',
                    'data-pjax' => '\'1\'', 'name' => $polje->naziv]);
                       
     }else{ //ako je samo A, B, C... onda pravi polje za unos
        echo Html::tag('div',Html::label($polje->naziv, $polje->naziv) 
                .Html::input('text', $polje->naziv, $veza->pojam->sadrzaj)
                ,['id' => $polje->naziv, 'class' => 'podPolje polje',
                    'name' => $polje->naziv]);
     }
}

