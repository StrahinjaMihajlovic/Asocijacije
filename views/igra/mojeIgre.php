<?php

/* 
 View za prikaz sopstvenih igara nekog korisnika
 */

/* @var $this \yii\web\View */
/* @var $reseneIgreModeli \app\models\ResenaIgra[] */
/* @var $igraAsocijacije \app\models\IgraAsocijacija */ 
/* @var $sopstveneIgre \app\models\Igra[]*/ 
use yii\helpers\Html;
use yii\bootstrap\BootstrapAsset;
$this->title = 'Moje igre';

?>

<div id='kontenerIgara'>
    <div id='sopstveneIgre'>
        
        <div id='naslov'>
            <h1>Igre koje sam kreirao:</h1> 
           
        </div>
        <?php
        
        foreach ($sopstveneIgre as $igra):
            $osobine = $igra->attributes;
        ?>
        <div id ='<?php echo $osobine['naziv']?>' class="igre">
            <h2 style='display: inline'><?php echo $osobine['naziv']?></h2>
            <?php 
            echo Html::a('Dopuni ili izmeni', yii\helpers\Url::to(
                    ['korisnicki-alati/kreiranjeasocijacije'
                        , 'trenIgra' => $osobine['id']])
                    , ['class' => "btn btn-default"]);
            echo Html::a('Izbrisi ovu igru', \yii\helpers\Url::to([
                'igra/izbrisi-igru', 'id' => $osobine['id']
            ],true),['class' => 'btn btn-warning']);
            ?>
        <p>Kategorija: <?php echo $igra->kategorija->naziv ?></p>
        <p style='display : inline-block'>Odobrena: <?php switch($osobine['aktivna']){
            case 0:
                 echo Html::tag('p',"Ceka na odobrenje",['class' => 'text-warning','style'=>'display: inline-block']);
                break;
            case 1:
                echo Html::tag('p',"Odobrena",['class' => 'text-success','style'=>'display: inline-block']);
                break;
            case -1:
                echo Html::tag('p',"Nije odobrena, molimo da izmenite asocijacije",['class' => 'text-danger','style'=>'display: inline-block']);
                break;
        } ?>
        </p>
        <p>Broj igranja: <?php echo $osobine['broj_igranja'] ?></p>
        </div>
        <?php
        endforeach;
        ?>
    </div>
    
    <div id='igraneIgre'>
        <div id='naslov' class='border-bottom'>
            <h1>Odigrane igre ili igre u toku:</h1>
        </div>
    <?php

    foreach ($reseneIgreModeli as $igra):
       $osobine = $igra->igra->attributes;
       ?>
    <div id='<?php echo $osobine['naziv'] ?>' class='igre'>
        <h2><?php echo $osobine['naziv']?></h2>
        <p>Kategorija: <?php echo $igra->igra->kategorija->naziv ?></p>
        <p>Zavrseno: <?php echo $igra->resene_asocijacije ?> od <?php 
              echo $igraAsocijacije->vratiSveAsocijacije($osobine['id'])->getCount();
        ?></p>
        <a href="<?php echo yii\helpers\Url::to(['igra/index', 'Igra' 
            => $osobine['id']]); ?>"><button>Nastavi</button></a>
    </div>
    <?php endforeach;
   
?></div>
</div>