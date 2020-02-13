<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\assets;

/**
 * Description of RasporedAsocijacijeAsset
 *
 * @author strahinja
 */
class RasporedAsocijacijeAsset extends \yii\web\AssetBundle{
    public $sourcePath = '@app/assets';
    public $js =[
        'js/RasporedAsoc.js'
    ];
    public $css = [
        'css/RasporedAsoc.css'
    ];
    public $depends = ['yii\web\JqueryAsset'];
    public $publishOptions = ['forceCopy' => true];
}
