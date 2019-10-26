<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
            <h2><?php echo $osobine['naziv']?></h2>
        <p>Kategorija: <?php echo $igra->kategorija->naziv ?></p>
        <p>Aktivna: <?php echo $osobine['aktivna'] ?></p>
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