<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelAsocijacija app\models\Asocijacija*/
/* @var $modelIgra app\models\Igra */
/* @var $modelPoljeVeza app\models\PojamPoljeAsocijacija */
/* @var $modelPojam yii\data\ActiveDataProvider */
/* @var $nizPojam app\models\Pojam\nosilacPodatka */
/* @var $modelResAsoc app\models\ResenaAsocijacija */
/* @var $modelResIgra app\models\ResenaIgra*/

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
function proveriAkoJeOtvoreno($poljeVeza, $resenaAsocijacijaModel){
    $idPolja = $poljeVeza->polje->id;
    if(preg_match($resenaAsocijacijaModel->vratiRegexZaAsocijaciju($idPolja),$resenaAsocijacijaModel->otvorena_polja)){
        return $poljeVeza->pojam->sadrzaj;
    }else if(preg_match('/\d/m', $poljeVeza->polje->naziv)){
        return '[otvori]'; //vrati otvori tekst ako naziv polja sadrzi broj.
    }else{
        return '';
    }
     
}
    ?><?php
function daLiJeIgraResena($modelResIgra, $modelResAsoc, $modelIgra){
    if((isset($modelResIgra) && (count($modelIgra->getAsocijacijas()->all()) 
                === intval($modelResIgra->resene_asocijacije)))&&
                $modelResAsoc->proveriDaLiJeResena()){
        return true;
    }
    return false;
}

?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo 'Asocijacija' .' broj '. ($modelResAsoc->proveriDaLiJeResena() ? 
        $modelResIgra->resene_asocijacije : ($modelResIgra->resene_asocijacije  + 1)) 
                . '/'.(count($modelIgra->asocijacijas));?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>      
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class ="x_content">
        
     
        
        <?php if(daLiJeIgraResena($modelResIgra, $modelResAsoc, $modelIgra)):?>
        <div id='cestitka'>
            <h1 class='text-success'>Čestitamo, rešili ste ovu igru!</h1>
             <button  class="btn btn-info" id ='novaIgra'>Pređi na novu igru</button>
          
        </div>
        <?php elseif($modelResAsoc->proveriDaLiJeResena()):?>
        <div id='cestitka'>
            <h1 class="text-success">Cestitamo, resili ste asocijaciju!</h1>
            <a href=""><button class='btn btn-info' id='sledecaAsoc'>Predji na novu asocijaciju</button></a>
            <?php $this->registerJs("$('#sledecaAsoc').on('click', function(){"
                    . "$.post(window.location, {sledecaAsoc : 1})"
                    . "})"); ?>
        </div>
        <?php endif;?>
       
            <div id='asocijacija' class="wrapAsocijacije">
         <?php
                 foreach ($modelPoljeVeza as $model){
                     $otvoren = proveriAkoJeOtvoreno($model, $modelResAsoc);
                     if($model->polje->naziv === "Resenje"){
                       echo Html::tag('div',Html::label($model->polje->naziv
                               , $model->polje->naziv).Html::input('text'
                                       , $model->polje->naziv, $otvoren) 
                               , ['id' => 'Resenje', 'class' => 'tekstPolje polje']);
                       
                   }else if(preg_match('/\d/', $model->polje->naziv)){
                       $broj = implode(preg_grep('/\d/', str_split($model->polje->naziv)));
                       $tip = implode(preg_grep('/\d/', str_split($model->polje->naziv), PREG_GREP_INVERT));
                       echo Html::tag('div',Html::label($model->polje->naziv
                               , $model->polje->naziv).Html::input('button'
                                       , $model->polje->naziv, $otvoren )
                               ,['data-value' => $broj, 'id' => $model->polje->naziv
                               , 'class' => $tip . ' polje', 'data-pjax' => '\'1\'', 'name' => $model->polje->naziv]);
                   }else{ //ako je samo A, B, C... onda pravi polje za unos
                       
                       echo Html::tag('div',Html::label($model->polje->naziv
                               , $model->polje->naziv).Html::input('text'
                                       , $model->polje->naziv, $otvoren),
                               ['id' =>$model->polje->naziv, 'class' => 'podPolje polje', 'name' => $model->polje->naziv]);
                   }
                 }
            ?>
                </div>
        </div>
            <?php
        
                $this->registerCssFile('@web/css/asocijacijeIndex.css');
                $this->registerCss(
                            ".polje>input[type='button']{"
                            . " min-width:10vw;"
                        . "}"
                        );
                $this->registerJsFile('@web/js/igraIndex.js',['depends' => [app\assets\RasporedAsocijacijeAsset::class]]);
                $this->registerJs("");
               
                ?>
       
    </div>
 </div>