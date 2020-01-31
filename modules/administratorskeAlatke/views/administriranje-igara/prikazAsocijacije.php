<?php
use yii\helpers\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*@var $model app\models\Asocijacija */
/*@var $this yii\web\view*/
echo 'xsa';
$pojamModel = new app\models\Pojam();
$modelVezaPojamAsocPolje = $model->vratiVezuPojamAsocijacijaPolje()->all();
foreach($modelVezaPojamAsocPolje as $veza){
    $polje = $veza->polja;
     if($polje->naziv === "Resenje"){
        echo Html::input('text', $polje->naziv, '',['id' => 'Resenje', 'class' => 'tekstPolje polje']);
                       
     }else if(preg_match('/\d/', $polje->naziv)){
        $broj = implode(preg_grep('/\d/', str_split($polje->naziv)));
        $tip = implode(preg_grep('/\d/', str_split($polje->naziv), PREG_GREP_INVERT));
        echo Html::input('text', $polje->naziv, ''
                ,['data-value' => $broj, 'id' => $polje->naziv, 'class' => $tip .' polje',
                    'data-pjax' => '\'1\'']);
                       
     }else{ //ako je samo A, B, C... onda pravi polje za unos
        echo Html::input('text', $polje->naziv, ''
                ,['id' => $polje->naziv, 'class' => 'podPolje polje']);
     }
}
$this->registerCss('.wrapAsocijacije{'
        . 'width:100%;'
        . 'height:50vh;'
        . '}'
        . '.polje{'
        . 'position:absolute;'
        . 'margin-top:1%;'
        . '}');
app\assets\RasporedAsocijacijeAsset::register($this);