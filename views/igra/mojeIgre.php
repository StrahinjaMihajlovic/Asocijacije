<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* @var $this \yii\web\View */
/* @var $reseneIgreModeli \app\models\ResenaIgra[] */
/* @var $igraAsocijacije \app\models\IgraAsocijacija */ 

use yii\helpers\Html;
$this->title = 'Moje igre';

?>

<div id='kontenerIgara'>
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
   
?>
</div>