<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Popunite sledece informacije da bi ste se ulogovali!</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-3 \">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
        ],
    ]); ?>

        <?php echo $form->field($model, 'username')->textInput(['autofocus' => true,
            
            ])->label('Korisnicko ime <br> ili e-mail'); ?>

        <?= $form->field($model, 'password')->passwordInput([
            
        ])->label('lozinka') ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ])->label('zapamti me') ?>

        <div class="form-group has-error">
            <div class="col-lg-offset-1 col-lg-1 ">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
                <div class='col-lg-8'>
                <?php 
                    if($model->getZabranjenoLogovanje()){
                        echo Html::tag('p', 'Uneli ste lozinku pogresno vise puta'
                                . ', molimo vas za 30 sekundi ponovo da pokusate'
                                , ['class' => 'help-block help-block-error']);
                    }
                ?>
                </div>
            
        </div>
    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        Ako nemate nalog, mozete ga napraviti klikom na <a href="<?php echo yii\helpers\Url::to(['site/signup']); ?>">link</a>.<br>
        Zaboravili lozinku? Resetujte istu na sledecem <a href="<?php echo yii\helpers\Url::to(['site/reset-lozinke']); ?>">linku</a>
    </div>
</div>
