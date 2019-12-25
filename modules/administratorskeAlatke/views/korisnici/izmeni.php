<?php
use yii\helpers\Html;
/*  @var $this yii\web\View */
?>
<div id='naslov'>
    <?= Html::tag('h1', 'Izmenite informacije clana ' . $clan->korisnicko_ime,[])?>
</div>
<?php $form = \yii\widgets\ActiveForm::begin();?>

<?= $form->field($clan, 'korisnicko_ime')->textInput()?>
<?= $form->field($clan, 'email')->textInput()?>
<?= $form->field($clan, 'lozinka_uneta')->textInput()?>
<?= $form->field($clan, 'aktivan')->checkbox()?>
<?= Html::submitButton('Izmeni')?>
<?php
$form->end();
