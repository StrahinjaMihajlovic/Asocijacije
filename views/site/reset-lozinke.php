<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id='sadrzaj'>
<?php
if(isset($potvrdjeno) && $potvrdjeno):?>
   Zahtev za reset lozinke prihvacen, proverite svoj mail.
   
   <?php elseif(isset($kodNevazeci)): ?>
   
   Kod je istekao!
   
   <?php elseif(isset($pogresanKod)):?>
   
   Kod nije ispravan, proverite vaš mail ponovo.
   
   <?php elseif(isset($novaLozinka) && $novaLozinka): ?>
   
   <?php $form = yii\widgets\ActiveForm::begin()?>
   
   <p>Unesite novu lozinku.</p>
   <?= $form->field($model, 'lozinka')->label('Nova lozinka')->textInput()?>
   <?= \yii\helpers\Html::submitButton('Posalji')?>
 <?php $form->end()?>
   
   <?php else: ?>
   <?php $form = yii\widgets\ActiveForm::begin()?>
   
   <p>Unesite svoj mail radi potvrde identita.</p>
   <?= $form->field($model, 'email')->label('E-mail')->textInput()?>
   <?= \yii\helpers\Html::submitButton('Posalji')?>
 <?php $form->end()?>
<?php endif; ?>
</div>