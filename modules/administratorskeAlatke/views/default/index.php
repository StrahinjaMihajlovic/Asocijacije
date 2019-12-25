<?php 
/* @var $this yii\web\View */
use yii\helpers\Html;
?>

<div id="sadrzaj">
   Izaberite jednu od sledecih opcija:
   <?= Html::a('Korisnici', yii\helpers\Url::to(['korisnici/index']), ['class' => 'btn btn-info'])?>
</div>