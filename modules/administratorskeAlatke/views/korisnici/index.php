<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
/*
 @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
//ukljucujemo u assetBundle sledeci js fajl, da bi mogli pristupiti istom. Ukloniti force copy kasnije
$url = yii::$app->getAssetManager()->publish( yii::getAlias("@adminAlatke")
        . '/assets',['forceCopy' => 1])[1].'/js/korisnici-index.js';

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'korisnicko_ime',
        'email',
        'aktivan',
        [
            'class' => yii\grid\ActionColumn::class,
            'buttons' => [
                'pregledaj' => function ($url, $model, $key){
                    return Html::a(Html::tag('span', ''
                            , ['class' => 'glyphicon glyphicon-eye-open']), $url
                            ,['class' => 'pregledaj','title' => 'Pregledaj'
                                , 'data-toggle' => 'modal'
                                , 'data-target' => '#prikazClana']);
                },
                'izbrisi' => function ($url, $model, $key){
                    return Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']), $url,['title' => 'Izbrisi', 'data-method' => 'post']);
                },
                'izmeni'=> function ($url, $model, $key){
                    return Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']), $url,['title' => 'Izmeni', 'target' => '_blank']);
                }
            ],
                    'template' => '{pregledaj} {izmeni} {izbrisi}'
        ]
    ],
])?>
 <?php Modal::begin([
        'header' => 'Detaljan prikaz za clana ',
     'id' => 'prikazClana'
    ]); ?>

<?php Modal::end()?>
<?php

$this->registerJsFile($url, ['depends' => yii\web\JqueryAsset::class]);
 


