<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this \yii\web\View */
/* @var $igra app\models\Igra */

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
<div id='interfejsKreiranje'>
    <?php if($igra->getAttribute('naziv')) :?>
    <h1 style ='color:red'>Igra nije uspesno kreirana, proverite svoj unos</h1>
    
    <?php endif;
    $form = ActiveForm::begin()?>
    <?= $form->field($igra, 'kategorija_id')->dropDownList($kategorije,['prompt'
        => 'Unesite kategoriju'])?>
    <?= $form->field($igra, 'naziv')->textInput(['class' => 'polje'])->label('Naziv igre: ')?>
    <?= $form->field($igra, 'opis')->textarea(['class' => 'tekst polje'])->label('Opis igre:')?>
    <?= Html::submitButton('Napravi igru',['class' => 'btn btn-primary'])?>    
    <?php ActiveForm::end()?>
</div>