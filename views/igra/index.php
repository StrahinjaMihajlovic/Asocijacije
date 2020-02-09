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
    if(preg_match("/^".$idPolja."[^\d]|[^\d]$idPolja"."[^\d]|[^\d]".$idPolja."$/m",$resenaAsocijacijaModel->otvorena_polja)){
        return $poljeVeza->pojam->sadrzaj;
    }else if(preg_match('/\d/m', $poljeVeza->pojam->sadrzaj)){
        return '[otvori]'; //vrati otvori tekst ako naziv polja sadrzi broj.
    }else{
        return '';
    }
     
}
    
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('app', 'Asocijacija') ?></h2>
        <ul class="nav navbar-right panel_toolbox" style="min-width: auto;">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>      
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class ="x_content">
        
        <?php //$form = ActiveForm::begin(['options' =>['style'=> 'position: relative']])?>
        
        <?php if(strstr($modelResAsoc->otvorena_polja, 'resenje') && (isset($modelResIgra)
                && (count($modelIgra->getAsocijacijas()->all()) === intval($modelResIgra->resene_asocijacije)))):?>
        <div id='cestitka'>
            <h1>Cestitamo, resili ste ovu igru!</h1>
             <button  class="btn btn-dark" id ='novaIgra'>Predji na novu igru</button>
          
        </div>
        <?php elseif(strstr($modelResAsoc->otvorena_polja, 'resenje')):?>
        <div id='cestitka'>
            <h1>Cestitamo, resili ste asocijaciju!</h1>
            <a href="" class='btn btn-info' id='novaAsoc'>Predji na novu asocijaciju</a>
        </div>
        <?php endif;?>
       
            <div id='asocijacija'>
         <?php
                 foreach ($modelPoljeVeza as $model){
                     $otvoren = proveriAkoJeOtvoreno($model, $modelResAsoc);
                     if($model->polje->naziv === "Resenje"){
                       echo Html::input('text', $model->polje->naziv, $otvoren , ['id' => 'Resenje', 'class' => 'tekstPolje polje']);
                       
                   }else if(preg_match('/\d/', $model->polje->naziv)){
                       $broj = implode(preg_grep('/\d/', str_split($model->polje->naziv)));
                       $tip = implode(preg_grep('/\d/', str_split($model->polje->naziv), PREG_GREP_INVERT));
                       echo Html::input('button', $model->polje->naziv
                               , $otvoren 
                               ,['data-value' => $broj, 'id' => $model->polje->naziv
                               , 'class' => $tip . ' polje', 'data-pjax' => '\'1\'']);
                   }else{ //ako je samo A, B, C... onda pravi polje za unos
                       
                       echo Html::input('text', $model->polje->naziv, $otvoren,['id' =>$model->polje->naziv, 'class' => 'podPolje polje']);
                   }
                 }
            ?>
                </div>
        </div>
            <?php
        //<?php //ActiveForm::end();
                $this->registerCssFile('@web/css/asocijacijeIndex.css');
                app\assets\RasporedAsocijacijeAsset::register($this);
                $this->registerJsFile('@web/js/igraIndex.js',['depends' => [\yii\web\JqueryAsset::className()]]);
                $this->registerJs("");
               
                ?>
       
    </div>
 </div>