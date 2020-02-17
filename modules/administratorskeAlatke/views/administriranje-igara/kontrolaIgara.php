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


if(isset($modeliIgara)):
$asocijacije = $modeliIgara->getAsocijacijas();?>
<div id='informacijeIgre' data-value='<?=$modeliIgara->id?>'>
    <h2>Igra "<?=$modeliIgara->naziv?>" od korisnika <?=$modeliIgara->getKreator()->one()->korisnicko_ime?></h2>
    <p style="display: inline-block">Prikazi više informacija</p>
    <a data-toggle='collapse' href="#collapse"><span class="caret"></span></a>
    <div id='collapse' class="collapse">
        <p>Opis igre: <?=$modeliIgara->opis?></p>
        <p>Kategorija: <?=$modeliIgara->kategorija->naziv?></p>
        <div style="border-bottom: 2px solid; margin: 1% 0px 1% 0px"></div>
    </div>
</div>
<div>
<?= ListView::widget([
    'dataProvider' => new yii\data\ActiveDataProvider(['query' => $asocijacije]),
    'itemView' => 'prikazAsocijacije',
    'itemOptions' => [
        'class' => 'wrapAsocijacije',
        'style' => 'position:relative',
    ]
]);?>
</div>
<div id='dugmad'>
<?=Html::a('Odobri', '', ['class' => 'btn btn-success', 'id' => 'odobreno'])?>
<?=Html::a('Odbij', '',['class' => 'btn btn-warning', 'id' => 'odbijeno'])?>
</div>

<?php
$this->registerJs("$('#odobreno').on('click',function(){"
        . "$.post(document.location,{igra:$('#informacijeIgre').data('value'), odobreno:1})"
        . "});"
        . "$('#odbijeno').on('click',function(){"
        . "$.post(document.location,{igra:$('#informacijeIgre').data('value'), odbijeno:1})"
        . "})");

?>

<?php
else:
?>
<div id='Obavestenje'>
    <h2 class="text-center text-success">Nema trenutno igara na cekanju, molimo vas da se vratite kasnije!</h2>
</div>
<?php endif; 
app\assets\RasporedAsocijacijeAsset::register($this);?>
