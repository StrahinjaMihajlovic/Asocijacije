<?php
/* @var $this yii\web\View */

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'signup-procedura'
]);
?>
<?= $form->field($model, 'email')->label('E-mail')->textInput()?>
<?= $form->field($model, 'korisnicko_ime')->label('korisnicko ime')->textInput(); ?>
<?= $form->field($model, 'lozinka')->label('Lozinka')->passwordInput();?>
<?= $form->field($model, 'lozinka_ponovljena')->label('Ponovi lozinku')->passwordInput(); ?>
<?= Html::submitButton('Registruj se')?>

<?php
ActiveForm::end();

