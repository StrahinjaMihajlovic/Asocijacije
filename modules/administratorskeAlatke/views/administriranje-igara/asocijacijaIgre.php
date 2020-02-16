<?php
use yii\widgets\ListView;
/*@var $this yii\web\view*/
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo ListView::widget([
    'dataProvider' => $asocijacije,
    'itemView' => 'prikazAsocijacije',
    'itemOptions' => [
        'class' => 'wrapAsocijacije',
        'style' => 'position:relative'
    ]
]);
app\assets\RasporedAsocijacijeAsset::register($this);
