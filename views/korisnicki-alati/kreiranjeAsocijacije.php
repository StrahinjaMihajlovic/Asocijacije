<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this \yii\web\View */
/* @var $polja app\models\Polje */
/* @var $pojam app\models\Pojam*/
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of kreiranjeIgre
 *
 * @author strahinja
 */

?>

<div id='asocijacija'>
    <?php if($nova):?>
    <h1>Igra je uspesno kreirana, sad mozete napraviti prvu asocijaciju u toj igri!</h1>
    <?php endif;?>
    
    <?php if($uspesnost):?> 
    <h1>Asocijacija je uspesno ubacena,
        mozete uneti novu asocijaciju za istu igru ili napraviti novu igru!</h1>
    <?php elseif($uspesnost === false):?>
    <h1 style='color: red'>Asocijacija nije ubacena u bazu, 
        molimo vas da popunite sva polja ili izmenite vasu asocijaciju!</h1>
    <?php endif;
    
    $form = ActiveForm::begin(['id' => 'asocijacija-form',
        'enableClientScript' => false]) ?>
                   <?php
                   $field = $form->field($polja[0], 'naziv[]');
                   
                   
               foreach ($polja as $str){ //pravimo svako dugme ponaosob
                  
                   $field->model = $str;
                     $field->options = ['class' => 'pojamwrap '];
                   $field->label($str->naziv);
                   if($str->naziv === 'Resenje'){
                       $field->options = ['class' => 'Resenje pojamwrap'];
                     echo  $field->textInput(
                               ['id' => 'Resenje', 'class' => 'tekstPolje'
                                   ,'value' => '']);
                       
                   }else if(preg_match('/\d/', $str->naziv)){
                       $broj = implode(preg_grep('/\d/', str_split($str->naziv)));
                       $tip = implode(preg_grep('/\d/', str_split($str->naziv), PREG_GREP_INVERT));
                       $field->options = ['class' => 'pojamwrap '. $tip, 
                        'data-value' => $broj];
                      echo $field->textInput
                               (['data-value' => $broj
                               , 'id' => $str->naziv
                               , 'class' => $tip
                               , 'value' => '']);
                   }else{ //ako je samo A, B, C... onda pravi polje za unos
                       
                       $field->options = [
                           'class' => 'pojamwrap',
                           'id' => $str->naziv
                       ];
                        echo  $field->textInput([
                            'id' => $str->naziv,
                            'class' => 'podpolje',
                            'value' => ''
                        ]);      
                   }
                }
                echo Html::submitButton('Unos', ['class' => 'btn btn-primary']);
                ActiveForm::end(); 
            ?>
  </div>
<?php 
    
    $this->registerJsFile('@web/js/korisnicki-alati/kreiranjeIgre.js',
            ['depends' => [\yii\web\JqueryAsset::className()]]);
    $this->registerCssFile('@web/css/korisnicki-alati/kreiranjeIgre.css');


