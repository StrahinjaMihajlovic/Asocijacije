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
    'filterModel' => $modelSearch,
    'columns' => [
        'naziv',
        'opis',
        [
            'attribute' => 'kategorija_id',
            'label' => 'Kategorija',
            'value' => function($model){
                return $model->kategorija->naziv;
            }
        ],
        [
            'attribute'=>'kreator_id',
            'label' => 'Autor',
            'value' => function($model){
                return $model->kreator->korisnicko_ime;
            }
        ],
        [
            'attribute' => 'aktivna',
            'value' => function ($model, $key, $index, $column){
                if($model->aktivna === 1){
                    return '<p class ="text-success">Odobrena</p>';
                }else if($model->aktivna === 0){
                    return '<p class ="text-warning">Ceka na odobrenje</p>';
                }else{
                    return '<p class ="text-danger">Nije odobrena</p>';
                }
            },
            'format' => 'html',
                    'filter' => [1 => 'Odobrena', 0 => 'Ceka na odobrenje', -1 => 'Nije odobrena']
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
                'neodobreno'=> function ($url, $model, $key){
                    return Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-remove']), $url,['title' => 'oznaci kao neodobreno', 'data-method' => 'post']);
                }
            ],
                    'template' => '{pregledaj} {neodobreno} {izbrisi}'
        ]
    ],
])?>
 <?php Modal::begin([
        'header' => 'Detaljan prikaz igre ',
     'id' => 'prikazIgre'
    ]); ?>

<?php Modal::end()?>
