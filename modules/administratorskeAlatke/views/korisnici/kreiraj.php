<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
?>
<div id='naslov'>  <h1>Popunite podatke novog clana</h1>  </div>
<?php
$form = \yii\widgets\ActiveForm::begin();
echo $form->field($korisnik, 'korisnicko_ime')->textInput();
echo $form->field($korisnik, 'email')->textInput();
echo $form->field($korisnik, 'lozinka_uneta')->textInput();
echo $form->field($korisnik, 'aktivan')->checkbox();
echo Html::submitButton('Kreiraj');
$form->end();
?>
