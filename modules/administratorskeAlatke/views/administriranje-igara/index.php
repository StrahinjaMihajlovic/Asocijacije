<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
app\modules\administratorskeAlatke\assets\gridviewAdminAsset::register(yii::$app->view);
?>

<?=
GridView::widget([
    'dataProvider' => $modelIgra,
    'columns' => [
        'id',
        'naziv',
        'opis',
        [
            'attribute' => 'kategorija.naziv',
            'label' => 'Kategorija'
        ],
        [
            'attribute'=>'kreator.korisnicko_ime',
            'label' => 'Autor'
        ],
        [
            'class' => yii\grid\ActionColumn::class,
                       'buttons' => [
                'pregledaj' => function ($url, $model, $key){
                    return Html::a(Html::tag('span', ''
                            , ['class' => 'glyphicon glyphicon-eye-open']), $url
                            ,['class' => 'pregledaj','title' => 'Pregledaj'
                                , 'data-toggle' => 'modal'
                                , 'data-target' => '#prikazIgre']);
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
        'header' => 'Detaljan prikaz igre ',
     'id' => 'prikazIgre'
    ]); ?>

<?php Modal::end()?>
