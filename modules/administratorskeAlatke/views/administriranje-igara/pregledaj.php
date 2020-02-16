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
        'broj_igranja',
        [
            'label' => 'Ukupan broj asocijacija',
            'value' => count($igra->asocijacijas) 
            . Html::a('<span class = \'glyphicon glyphicon-cog\'></span>', yii\helpers\Url::to(['asocijacije-igre', 'igraId' => $igra->id], true)
                    ,['data-pjax' => "0",'target' => '_blank', 'rel'=>"noopener noreferrer"]),
            'format' => 'raw' // mozda nije bas sigurno?
        ]
    ]
]);