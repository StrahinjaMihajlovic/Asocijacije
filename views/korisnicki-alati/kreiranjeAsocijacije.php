<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this \yii\web\View */
/* @var $polja app\models\PojamPoljeAsocijacija[] */
/* @var $pojam app\models\Pojam*/
/* @var $kreiranjeAsocijacije app\models\posebni_modeli\kreiranjeAsocijacije*/ 

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
<div id='Naslov'>
    <h1 >Pregled i izmena igre ,,<?= $kreiranjeAsocijacije->igra->naziv?>"</h1>
    <p style='display: inline-block'>Informacije o igri</p><a data-toggle='collapse' href='#collapse'><span class='caret'></span></a>
    <div id='collapse' class='collapse'>
        <?php $igraModel = $kreiranjeAsocijacije->igra; 
        $formIgra = ActiveForm::begin([
            'id' => 'igra-form',
            'action' => \yii\helpers\Url::to(['/korisnicki-alati/azuriranje-igre', 'id' => $kreiranjeAsocijacije->igra->id], true)
        ])?>
        <?=$formIgra->field($igraModel, 'naziv')?>
        <?=$formIgra->field($igraModel, 'opis')?>
        <?=$formIgra->field($igraModel, 'kategorija_id')->dropDownList(app\models\Kategorija::vratiKategorijeNiz())?>
        <?= Html::submitButton('Azuriraj informacije',['class' => 'btn btn-info'])?>
        <?php ActiveForm::end()?>
        <div style='border-bottom: 2px solid; margin: 1% 0px 1% 0px'></div>
    </div>
</div>

<div id="navigacijaAsoc">
    <?php echo !isset($kreiranjeAsocijacije->asocijacija->id) 
            || (nadjiPozicijuAsoc($kreiranjeAsocijacije->asocijacija, $kreiranjeAsocijacije) > 1) 
    ? Html::button('Nazad', ['id' => 'nazad'])  :  ""?>
    
    <p style='display: inline-block'><?php echo isset($kreiranjeAsocijacije->asocijacija->id) //pratimo na kojoj asocijaciji po redu smo trenutno
            ? nadjiPozicijuAsoc($kreiranjeAsocijacije->asocijacija, $kreiranjeAsocijacije) 
            . '/' . count($kreiranjeAsocijacije->AsocijacijeUIgri)
            : 'Nova asocijacija'
            ?></p>
    
    <?php echo isset($kreiranjeAsocijacije->asocijacija->id) 
    ? Html::button('Napred', ['id' => 'napred']) : ''?>
    
</div>
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
                   
                   
               foreach ($polja as $poljeVeza){ //pravimo svako dugme ponaosob
                   $popunjenoPolje = '';
                  
                   
                   if(isset($kreiranjeAsocijacije->asocijacija->id)){
                       $popunjenoPolje = $poljeVeza->pojam->sadrzaj;
                   }
                   
                   $polje = '';
                   if($poljeVeza->getIsNewRecord()){
                       $polje = (\app\models\Polje::findOne(['id' => $poljeVeza->id_polja]));
                   }else{
                       $polje = $poljeVeza->polje;
                   }
                   
                   $field->model = $polje;
                     $field->options = ['class' => 'pojamwrap '];
                   $field->label($polje->naziv);
                   
                   
                   
                   if($polje->naziv === 'Resenje'){
                       $field->options = ['class' => 'Resenje pojamwrap'];
                     echo  $field->textInput(
                               ['id' => 'Resenje', 'class' => 'tekstPolje'
                                   ,'value' => $popunjenoPolje]);
                       
                   }else if(preg_match('/\d/', $polje->naziv)){
                       $broj = implode(preg_grep('/\d/', str_split($polje->naziv)));
                       $tip = implode(preg_grep('/\d/', str_split($polje->naziv), PREG_GREP_INVERT));
                       $field->options = ['class' => 'pojamwrap '. $tip, 
                        'data-value' => $broj];
                      echo $field->textInput
                               (['data-value' => $broj
                               , 'id' => $polje->naziv
                               , 'class' => $tip
                               , 'value' => $popunjenoPolje]);
                   }else{ //ako je samo A, B, C... onda pravi polje za unos
                       
                       $field->options = [
                           'class' => 'pojamwrap',
                           'id' => $polje->naziv
                       ];
                        echo  $field->textInput([
                            'id' => $polje->naziv,
                            'class' => 'podpolje',
                            'value' => $popunjenoPolje
                        ]);      
                   }
                }
                echo Html::tag('div',Html::submitButton('Unos', ['class' => 'btn btn-primary']), ['style' => 'dipslay:block;']);
                ActiveForm::end(); 
            ?>
  </div>
<?php 
    
    $this->registerJsFile('@web/js/korisnicki-alati/kreiranjeIgre.js',
            ['depends' => [\yii\web\JqueryAsset::className()]]);
    $this->registerCssFile('@web/css/korisnicki-alati/kreiranjeIgre.css');
    $this->registerJs("$('#nazad').on('click', function(){"
            . "$.post(window.location, {nazad: 1}).fail("
            . "function(xhr, status, error){"
            . "console.log(xhr);"
            . "})});"
            . "$('#napred').on('click', function(){"
            . "$.post(window.location, {napred: 1}).fail("
            . "function(xhr, status, error){"
            . "console.log(xhr);"
            . "})});");

/* @var $asocijacija app\models\asocijacija */
function nadjiPozicijuAsoc($asocijacija, $kreiranjeAsocijacije){
    return array_search($asocijacija->id
            , array_column($kreiranjeAsocijacije->AsocijacijeUIgri, 'id')) + 1;
}

//app\assets\RasporedAsocijacijeAsset::register($this);