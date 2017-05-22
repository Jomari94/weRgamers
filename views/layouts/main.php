<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\JsAsset;
use app\assets\FontAsset;
use app\models\Notification;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\ListView;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
FontAsset::register($this);
JsAsset::register($this);

$urlConversations = Url::to(['/conversations/index']);
$urlNotifications = Url::to(['/site/notificated']);
$urlUsers = Url::to(['/site/bloodhound-users']) . '?q=%QUERY';
$urlGames = Url::to(['/site/bloodhound-games']) . '?q=%QUERY';
$userLink = Url::to(['/user/profile/show']) . '?id=';
$gameLink = Url::to(['/games/view']) . '?id=';
$score = Yii::t('app', 'Score');
$games = Yii::t('app', 'Games');
$users = Yii::t('app', 'Users');
$reviews = Yii::t('app', 'Reviews');
$followers = Yii::t('app', 'Followers');
$jsHead = <<<EOT
var urlConversations = "$urlConversations";
var urlNotifications = "$urlNotifications";
var urlUsers = "$urlUsers";
var urlGames = "$urlGames";
var userLink = "$userLink";
var gameLink = "$gameLink";
var nameScore = "$score";
var nameGames = "$games";
var nameUsers = "$users";
var nameReviews = "$reviews";
var nameFollowers = "$followers";
EOT;
$this->registerJs($jsHead, View::POS_HEAD);
$js = <<<EOT
$('#messages-link').on('click', function () {
    var ventana = open(urlConversations, "ventana", "width=600,height=640,toolbar=0,titlebar=0");
});

$('#notifications-link').on('click', function () {
    $("#modal").modal("show");
    $.ajax({
        method: 'post',
        url: urlNotifications,
    });
});
EOT;
$this->registerJs($js);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="https://use.fontawesome.com/02658bb44e.js"></script>
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
            'class' => 'navbar navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            '<form class="navbar-form navbar-left" method="GET" action="'.Url::to(['/site/search']).'">
                <div class="form-group search-form">
                    <input type="text" name="q" class="form-control typeahead" placeholder="'.Yii::t('app', 'Buscar usuarios, grupos...') .'">
                </div>
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </form>',
            [
                'label' => Yii::t('app', 'Messages'),
                'linkOptions' => ['id' => 'messages-link'],
                'visible' => !Yii::$app->user->isGuest,
            ],
            [
                'label' => Yii::t('app', 'Notifications'),
                'linkOptions' => ['id' => 'notifications-link'],
                'visible' => !Yii::$app->user->isGuest,
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
<?php
    $notificationsProvider = new ActiveDataProvider([
        'query' => Notification::find()->where(['id_receiver' => Yii::$app->user->id]),
        'pagination' => false,
    ]);
    Modal::begin(['id' => 'modal',
       'header' => '<h3>'.Yii::t('app', 'Notifications').'</h3>']);

        echo ListView::widget([
            'dataProvider' => $notificationsProvider,
            'itemOptions' => [
                'tag' => 'article',
                'class' => 'notification-view'
            ],
            'options' => [
                'tag' => 'div',
                'class' => 'notifications-wrapper',
                'id' => 'notifications-wrapper',
            ],
            'layout' => "{items}\n{pager}",
            'itemView' => '@app/views/notifications/_view.php',
        ]);

    Modal::end();
   ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
