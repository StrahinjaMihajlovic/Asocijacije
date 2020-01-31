<?php
use yii\widgets\ListView;
Use yii\helpers\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*@var $this yii\web\View */
/*@var $modeliIgara app\models\Igra*/

$asocijacije = $modeliIgara->getAsocijacijas();?>
<div id='informacijeIgre'>
    <h2>Igra "<?=$modeliIgara->naziv?>" od korisnika <?=$modeliIgara->getKreator()->one()->korisnicko_ime?></h2>
</div>
<div>
<?= ListView::widget([
    'dataProvider' => new yii\data\ActiveDataProvider(['query' => $asocijacije]),
    'itemView' => 'prikazAsocijacije',
    'itemOptions' => [
        'class' => 'wrapAsocijacije'
    ]
]);?>
</div>
<?=Html::a('Odobri', '', ['class' => 'btn btn-success', 'id'=>'odobreno'])?>
<?=Html::a('Odbij', '', ['class' => 'btn btn-warning', 'id'=>'odbijeno'])?>