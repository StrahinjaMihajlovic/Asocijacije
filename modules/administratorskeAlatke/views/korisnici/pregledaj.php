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
            'korisnicko_ime',
            'email',
            'reset_kod',
            'auth_key'
        ]
    ]) ?>
    
