<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\helpers\Html;
echo \yii\widgets\DetailView::widget([
    'model' => $igra,
    'attributes' => [
        'naziv',
        'opis',
        'kategorija.naziv',
        'kreator.korisnicko_ime',
        [
            'label' => 'Broj asocijacija',
            'value' => count($igra->asocijacijas) . Html::a('<span class = \'glyphicon glyphicon-th-large\'></span>', '',['target' => '_blank']),
            'format' => 'Html'
        ]
    ]
]);