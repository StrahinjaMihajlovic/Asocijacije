<?php

/* @var $this yii\web\View */

$this->title = 'Igra asocijacija';
?>
<div class="site-index d-flex justify-content-center">
    <h1 class='text-center'>Igra asocijacija</h1>
    <h1  class='text-center' >Kliknite na dugme ispod da bi pokrenuli igru.</h1>
    <a href='<?php echo yii\helpers\Url::to(['igra/index']) ?>'><button type="button" class="btn btn-dark" 
            style='position:relative; left:40%; top:10vh; width: 20%;
            height: 20vh; font-size: 5vh;'>Pokreni</button></a>
</div>
