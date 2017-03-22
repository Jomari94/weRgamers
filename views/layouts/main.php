<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\FontAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
FontAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            [
                'label' => Yii::t('app', 'Management'),
                'url' => ['/game/index'],
                'items' => [
                    [
                       'label' => 'Games',
                       'url' => ['/game/index'],
                    ],
                    [
                       'label' => 'Users',
                       'url' => ['/user/admin/index']
                    ],
                ],
                'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin],
            Yii::$app->user->isGuest ?
            ['label' => 'Sign in', 'url' => ['/user/security/login']]:
            [
                'label' => (Yii::$app->user->identity->username . ' ' .
                Html::img(Yii::$app->user->identity->profile->getAvatar(), ['class' => 'img-rounded img32'])),
                'url' => ['/user/profile/show', 'id' => Yii::$app->user->id],
                'encode' => false,
                'items' => [
                    [
                       'label' => 'My Profile',
                       'url' => ['/user/' . Yii::$app->user->id],
                    ],
                    [
                       'label' => 'Configuration',
                       'url' => ['/user/settings/profile']
                    ],
                    '<li class="divider"></li>',
                    [
                       'label' => 'Logout',
                       'url' => ['/user/security/logout'],
                       'linkOptions' => ['data-method' => 'post'],
                    ],
                ],
            ],
            ['label' => 'Sign Up', 'url' => ['/user/register'], 'linkOptions' => ['class' =>'blanco'],'visible' => Yii::$app->user->isGuest],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?=Yii::$app->name . ' ' . date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
