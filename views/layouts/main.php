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
            'class' => 'navbar navbar-fixed-top',
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
                       'label' => Yii::t('app', 'Games'),
                       'url' => ['/games/index'],
                    ],
                    [
                       'label' => Yii::t('app', 'Users'),
                       'url' => ['/user/admin/index']
                    ],
                ],
                'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin
            ],
            ['label' => Yii::t('app', 'Groups'), 'url' => ['/groups/index']],
            Yii::$app->user->isGuest ?
            ['label' => Yii::t('app', 'Sign in'), 'url' => ['/user/security/login']]:
            [
                'label' => (Yii::$app->user->identity->username . ' ' .
                Html::img(Yii::$app->user->identity->profile->getAvatar(), ['class' => 'img-rounded img32'])),
                'url' => ['/user/profile/show', 'id' => Yii::$app->user->id],
                'encode' => false,
                'items' => [
                    [
                       'label' => Yii::t('app', 'My Profile'),
                       'url' => ['/user/' . Yii::$app->user->id],
                    ],
                    [
                       'label' => Yii::t('app', 'Configuration'),
                       'url' => ['/user/settings/profile']
                    ],
                    '<li class="divider"></li>',
                    [
                       'label' => Yii::t('app', 'Logout'),
                       'url' => ['/user/security/logout'],
                       'linkOptions' => ['data-method' => 'post'],
                    ],
                ],
            ],
            ['label' => Yii::t('app', 'Sign up'), 'url' => ['/user/register'], 'linkOptions' => ['class' =>'blanco'],'visible' => Yii::$app->user->isGuest],
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
        <div class="pull-right">
            <?= Html::beginForm('/site/language') ?>
            <div class="col-xs-8">
                <?= Html::dropDownList('language', Yii::$app->language, ['en-US' => 'English', 'es-ES' => 'Spanish'], ['class' => 'form-control']) ?>
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton('Change', ['class' => 'btn btn-default']) ?>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</footer>
<div class="post-footer">

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
