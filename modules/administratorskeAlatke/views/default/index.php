<?php 
/* @var $this yii\web\View */
use yii\helpers\Html;
?>

<div id="sadrzaj">
   Izaberite jednu od sledecih opcija:
   <?= Html::a('Administriranje korisnika', yii\helpers\Url::to(['korisnici/index']), ['class' => 'btn btn-info'])?>
   <?= Html::a('Administriranje igara', yii\helpers\Url::to(['administriranje-igara/index']), ['class' => 'btn btn-info'])?>
   <?= Html::a('Odobravanje pristiglih asocijacija', yii\helpers\Url::to(['odobravanje-asocijacija/index']), ['class' => 'btn btn-info'])?>
</div>