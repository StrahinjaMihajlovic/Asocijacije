<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Igre asocijacija',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Napravi novu igru'
                , 'url' => ['/korisnicki-alati/kreiranjeigre']
                , 'visible' => !\yii::$app->user->getIsGuest()],
            ['label' => 'Moje igre', 'url' => ['/igra/mojeigre'],
                'visible' => !\yii::$app->user->getIsGuest()],
            
            ['label' => 'Administracija'
                , 'url' => (new yii\web\UrlManager())
                ->createAbsoluteUrl('administratorske-alatke/default/index'),
            'visible' => (!\yii::$app->user->getIsGuest()
                        && \app\models\User::findOne(yii::$app->user->getId())->getIsAdministrator())
                ],
            
            ['label' => 'Informacije', 'items'=>
                [['label' => 'O aplikaciji', 'url' => ['/site/about']],
                ['label' => 'Kontakt', 'url' => ['/site/contact']]]
                ],
            
            ['label' => 'Profil', 'url' => ['/korisnicki-alati/moj-profil']
                ,'visible' => !\yii::$app->user->getIsGuest()
            ],
            
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->korisnicko_ime . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Strahinja Mihajlović <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
