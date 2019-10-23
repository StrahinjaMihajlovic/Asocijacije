<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelAsocijacija app\models\Asocijacija*/
/* @var $modelIgra app\models\Igra */
/* @var $modelPolje yii\data\ActiveDataProvider */
/* @var $modelPojam yii\data\ActiveDataProvider */
/* @var $nizPojam app\models\Pojam\nosilacPodatka */
/* @var $modelResAsoc app\models\ResenaAsocijacija */

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
function proveriAkoJeOtvoreno($nazivPolja, $resenaAsocijacijaModel, $duzina, $nizPojam){
    //echo $nazivPolja . ' '. $resenaAsocijacijaModel->otvorena_polja . ' - ';
    
    try{
    if(!preg_match('/' .$nazivPolja . ',|'.$nazivPolja.'$/',$resenaAsocijacijaModel->otvorena_polja) 
            && strstr($resenaAsocijacijaModel->otvorena_polja, 'resenje') === false){
                return '[otvori]';
            }
    
    
     // note: delim_capture se ne ponasa predvidljivo, moras dodati zagrade
     // /()/ u regex-u
     $polje = preg_split('/([\d])/', $nazivPolja, 0,PREG_SPLIT_DELIM_CAPTURE); 
     //izracunamo vrednost slova kao a = 0, b = 1... pa onda dodamo broj koji 
     //oznacava redosled polja da bi dobili poziciju tog polja iz kolone
     //"pojmovi ids" u tabeli "asocijacija"
     $vrednost = ((ord(strtolower($polje[0])) - 97) * ($duzina + 1)) +
             (array_key_exists(1, $polje) ? intval($polje[1]) : $duzina + 1);
     echo $duzina;
     $trazeniPojamId = 0;
     if(strstr($resenaAsocijacijaModel->otvorena_polja, 'resenje') !== false && $nazivPolja === 'Resenje'){
         $trazeniPojamId = $nizPojam->RedosledPojmova[0];
     }else{
         $trazeniPojamId = $nizPojam->RedosledPojmova[$vrednost];
     }
     
     foreach($nizPojam->nizPojmova as $pojam){
         if(($pojam['id'] == $trazeniPojamId)){
             return $pojam['sadrzaj'];
         }
     }
    } catch (\yii\base\ErrorException $e){
     return 'greska';
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
        <?php Pjax::begin([
            'enablePushState' => false
        ])?>
        <?php //$form = ActiveForm::begin(['options' =>['style'=> 'position: relative']])?>
        
        <?php if(strstr($modelResAsoc->otvorena_polja, 'resenje')):?>
        <div id='cestitka'>
            <h1>Cestitamo, resili ste asocijaciju!</h1>
            <a href="<?php echo \yii\helpers\Url::to(['igra/index']);?>">
                <button class="btn btn-dark">Predji na novu igru</button>
            </a>
        </div>
        <?php endif;?>
        
            <?php
                $modeli = $modelPolje->getModels();
                
               
                ?><div id='asocijacija'>
                   <?php
               foreach ($modeli as $str){ //pravimo svako dugme ponaosob
                    $otvoren = proveriAkoJeOtvoreno($str->naziv //ako je A1,B3 ... onda pravi dugme
                               , $modelResAsoc, intval($sablonDimenzije), $nizPojam);
                   if($str->naziv === "Resenje"){
                       echo Html::input('text', $str->naziv, strcmp($otvoren, '[otvori]') ? $otvoren : '' , ['id' => 'Resenje', 'class' => 'tekstPolje']);
                       
                   }else if(preg_match('/\d/', $str->naziv)){
                       $broj = implode(preg_grep('/\d/', str_split($str->naziv)));
                       $tip = implode(preg_grep('/\d/', str_split($str->naziv), PREG_GREP_INVERT));
                       echo Html::input('button', $str->naziv
                               , $otvoren 
                               ,['data-value' => $broj, 'id' => $str->naziv
                               , 'class' => $tip, 'data-pjax' => '\'1\'']);
                   }else{ //ako je samo A, B, C... onda pravi polje za unos
                       
                       echo Html::input('text', $str->naziv, strcmp($otvoren, '[otvori]') ? $otvoren : ''
                               ,['id' => $str->naziv, 'class' => 'podPolje']);
                   }
                }
                
            ?>
                </div>
        <?php //ActiveForm::end();
                $this->registerCssFile('@web/css/asocijacijeIndex.css');
                $this->registerJsFile('@web/js/igraIndex.js',['depends' => [\yii\web\JqueryAsset::className()]]);
               
                ?>
        <?php Pjax::end()?>
    </div>
 </div>