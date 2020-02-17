<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
/*
 @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
//ukljucujemo u assetBundle sledeci js fajl, da bi mogli pristupiti istom. Ukloniti force copy kasnije
/*$url = yii::$app->getAssetManager()->publish( yii::getAlias("@adminAlatke")
        . '/assets',['forceCopy' => 1])[1].'/js/Gridview-modal-index.js';*/

     app\modules\administratorskeAlatke\assets\gridviewAdminAsset::register($this);
?>

<div id='naslov'>
    <h1>Prikaz korisnika</h1>
</div>

<?= Html::a('Kreiraj novog korisnika', ['kreiraj'], ['class' => 'btn btn-success'])?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $dataFilter,
    'columns' => [
        [ 
          "attribute" => 'korisnicko_ime',
          'label' => 'Korisničko ime'
        ],
        'email',
        [
            'attribute' => 'prebivaliste',
            'label' => 'Prebivalište'
        ],
        [
            'attribute' => 'aktivan',
            'label' => 'Aktivan',
            'value' => function ($model){
                    if($model->aktivan === 1){
                        return '<p class ="text-success">Aktivan</p>';
                    }else{
                        return '<p class="text-danger">Nije aktivan</p>';
                    }
            },
                    'format' => 'html',
                    'filter' => [0 => 'Nije aktivan', 1 => 'Aktivan']
        ],
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

//$this->registerJsFile($url, ['depends' => yii\web\JqueryAsset::class]);
 


