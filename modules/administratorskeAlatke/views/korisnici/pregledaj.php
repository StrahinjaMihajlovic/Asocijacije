<?php 
/* @var $this yii\web\View */
/* @var $model app\models\Korisnik*/
use yii\helpers\Html;
use yii\widgets\DetailView;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

   
    <?=    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'korisnicko_ime',
                'label' =>"Korisničko ime"
            ],
            'email',
            [
                'attribute' => 'prebivaliste',
                'label' => 'Prebivalište'
            ],
            'zanimanje',
            [
                'attribute' => 'pol',
                'value' => function($model){
                    if($model->pol == 0){
                        return 'Muski';
                    }else{
                        return 'Ženski';
                    }
                }
            ],
            'reset_kod',
            [
                'attribute' => 'aktivan',
                'value' => function ($model){
                    if($model->aktivan == 1){
                        return '<p class ="text-success">Aktivan</p>';
                    }else{
                        return '<p class="text-danger">Nije aktivan</p>';
                    }
                    
            },'format' => 'html'
            ]
        ]
    ]) ?>
    
