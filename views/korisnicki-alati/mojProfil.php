<?php

/*@var $this yii\web\View*/
/*@var $korisnik app\models\Korisnik*/
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div id='naslov'>
    <?php if(isset($uspesnost)){
        echo $uspesnost? Html::tag('p', 'Promene su uspesno sacuvane!'
                ,['class' => 'text-success'])
                :Html::tag('p', 'Promene nisu sacuvane!',['class' => 'text-danger']);
    }
?>
    <h1>Pozdrav, <?=$korisnik->getAttribute('korisnicko_ime')?>!</h1>
    <p>Pregledajte i izmenite Vase informacije:</p>
</div>
<div id="formaWrap">
    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => ['korisnicki-alati/moj-profil']
    ])?>
        <?=$form->field($korisnik,'korisnicko_ime')?>
        <?=$form->field($korisnik,'email')->input('email')?>
        <?=$form->field($korisnik,'prebivaliste')?>
        <?=$form->field($korisnik,'pol')->dropDownList([
            0 => 'muški',
            1 => 'ženski'
        ])?>
        <?=$form->field($korisnik,'datum_rodjenja')->input('date')?>
        <?=$form->field($korisnik,'zanimanje')?>
        <?=$form->field($korisnik,'lozinka_uneta')->label('Nova lozinka')?>
        <?= Html::submitButton('Izmeni moj profil', ['class' => 'btn btn-info'])?>
    <?php $form->end()?>
</div>

