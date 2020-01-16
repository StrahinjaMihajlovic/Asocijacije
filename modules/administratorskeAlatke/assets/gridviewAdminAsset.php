<?php
namespace app\modules\administratorskeAlatke\assets;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gridviewAdminAsset
 *
 * @author strahinja
 */
class gridviewAdminAsset extends \yii\web\AssetBundle{
    public $sourcePath = '@adminAlatke/assets';
    public $js =[
        'js/Gridview-modal-index.js'
    ];
    public $css = [
        
    ];
    public $depends = ['yii\web\JqueryAsset'];
}
