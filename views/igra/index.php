<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelAsocijacija app\models\Asocijacija*/
/* @var $modelIgra app\models\Igra */
/* @var $modelPolje yii\data\ActiveDataProvider */
/* @var $modelPojam app\models\Pojam */
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
        
        <?php $form = ActiveForm::begin(['options' =>['style'=> 'position: relative']])?>
            <?php
                $modeli = $modelPolje->getModels();
                
               // var_dump($modeli);
                ?><div id='asocijacija'>
                   <?php
               foreach ($modeli as $str){
                  
                   if($str->naziv === "Resenje"){
                       echo Html::input('text', $str->naziv, '', ['id' => 'Resenje', 'class' => 'tekstPolje']);
                       
                   }else if(preg_match('/\d/', $str->naziv)){
                       $broj = implode(preg_grep('/\d/', str_split($str->naziv)));
                       $tip = implode(preg_grep('/\d/', str_split($str->naziv), PREG_GREP_INVERT));
                       echo Html::input('button', $str->naziv, '',['data-value' => $broj, 'id' => $str->naziv, 'class' => $tip]);
                   }else{
                       
                       echo Html::input('text', $str->naziv, '',['id' => $str->naziv, 'class' => 'podPolje']);
                   }
                }
                
            ?>
                </div>
        <?php ActiveForm::end();
                $this->registerCssFile('@web/css/asocijacijeIndex.css');
                $this->registerJsFile('@web/js/igraIndex.js',['depends' => [\yii\web\JqueryAsset::className()]]);
               
                ?>
    </div>
 </div>