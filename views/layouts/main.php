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
$('.messages-link').on('click', function () {
    var ventana = open(urlConversations, "ventana", "width=600,height=640,toolbar=0,titlebar=0");
});

$('.notifications-link').on('click', function () {
    $("#modal").modal("show");
    $.ajax({
        method: 'post',
        url: urlNotifications,
    });
});

$(".menu-opener").click(function(){
  $(".menu-opener, .menu-opener-inner, .menu").toggleClass("active");
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
    <header>
        <div>
            <nav>
                <div class="menu-lateral">
                    <div class="menu-opener">
                        <div class="menu-opener-inner"></div>
                    </div>
                    <div class="menu">
                        <ul class="menu-inner">
                            <a href="<?= Url::to(['/groups/index']) ?>" class="menu-link">
                                <li><?= Yii::t('app', 'Groups') ?></li>
                            </a>
                            <?php if (Yii::$app->user->isGuest): ?>
                                <a href="<?= Url::to(['/user/security/login']) ?>" class="menu-link">
                                    <li><?= Yii::t('app', 'Sign in') ?></li>
                                </a>
                                <a href="<?= Url::to(['/user/register']) ?>" class="menu-link">
                                    <li><?= Yii::t('app', 'Sign up') ?></li>
                                </a>
                            <?php else: ?>
                                <span class="menu-link messages-link">
                                    <li><?= Yii::t('app', 'Messages') ?></li>
                                </span>
                                <span class="menu-link notifications-link">
                                    <li><?= Yii::t('app', 'Notifications') ?></li>
                                </span>
                                <a href="<?= Url::to(['/user/' . Yii::$app->user->id]) ?>" class="menu-link">
                                    <li><?= Yii::t('app', 'My Profile') ?></li>
                                </a>
                                <a href="<?= Url::to(['/user/settings/profile']) ?>" class="menu-link">
                                    <li><?= Yii::t('app', 'Configuration') ?></li>
                                </a>
                                <a href="<?= Url::to(['/user/security/logout']) ?>" data-method="post" class="menu-link">
                                    <li><?= Yii::t('app', 'Logout') ?></li>
                                </a>
                            <?php endif; ?>
                            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin): ?>
                                <a href="<?= Url::to(['/games/index']) ?>" class="menu-link">
                                    <li><?= Yii::t('app', 'Games') ?></li>
                                </a>
                                <a href="<?= Url::to(['/user/admin/index']) ?>" class="menu-link">
                                    <li><?= Yii::t('app', 'Users') ?></li>
                                </a>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div>
                    <a href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->name ?></a>
                    <a href="<?= Yii::$app->homeUrl ?>">W 'r' G</a>
                </div>
                <div>
                    <a href="<?= Url::to(['/groups/index']) ?>"><span class="fa fa-users"></span> <span><?= Yii::t('app', 'Groups') ?></span></a>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <a href="<?= Url::to(['/user/security/login']) ?>"><span class="fa fa-sign-in"></span> <span><?= Yii::t('app', 'Sign in') ?></span></a>
                        <a href="<?= Url::to(['/user/register']) ?>"><span class="fa fa-user-plus"></span> <span><?= Yii::t('app', 'Sign up') ?></span></a>
                    <?php else: ?>
                        <span class="messages-link"><span class="fa fa-envelope"></span> <span><?= Yii::t('app', 'Messages') ?></span></span>
                        <span class="notifications-link"><span class="fa fa-bell"></span> <span><?= Yii::t('app', 'Notifications') ?></span></span>
                        <span class="nav-expand">
                            <?= Html::img(Yii::$app->user->identity->profile->getAvatar(), ['class' => 'img-rounded img32']) ?> <span><?= Yii::$app->user->identity->username ?></span>
                            <div class="nav-dropdown nav-user">
                                <a href="<?= Url::to(['/user/' . Yii::$app->user->id]) ?>"><?= Yii::t('app', 'My Profile') ?></a>
                                <a href="<?= Url::to(['/user/settings/profile']) ?>"><?= Yii::t('app', 'Configuration') ?></a>
                                <hr />
                                <a href="<?= Url::to(['/user/security/logout']) ?>" data-method="post"><?= Yii::t('app', 'Logout') ?></a>
                            </div>
                        </span>
                    <?php endif; ?>
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin): ?>
                        <span class="nav-expand">
                            <span class="fa fa-area-chart"></span> <span><?= Yii::t('app', 'Management') ?></span>
                            <div class="nav-dropdown nav-manage">
                                <a href="<?= Url::to(['/games/index']) ?>"><?= Yii::t('app', 'Games') ?></a>
                                <a href="<?= Url::to(['/user/admin/index']) ?>"><?= Yii::t('app', 'Users') ?></a>
                            </div>
                        </span>
                    <?php endif; ?>
                </div>
            </nav>
            <div id="nav-search-form">
                <form class="navbar-form" method="GET" action="<?= Url::to(['/site/search']) ?>" role="search">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control typeahead" title="Search" aria-label="Search" />
                        <div class="input-group-btn">
                            <button type="submit" id="search-submit" class="btn btn-default"><span class="fa fa-search"></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer>
    <p>&copy; <?=Yii::$app->name . ' ' . date('Y') ?></p>
    <div id="lang-selector">
        <?= Html::beginForm('/site/language') ?>
        <div class="col-xs-8">
            <?= Html::dropDownList('language', Yii::$app->language, ['en-US' => 'English', 'es-ES' => 'Spanish'], [
                'class' => 'form-control',
                'aria-label' => 'Language selector',
                'title' => 'Language selector',
                ]) ?>
        </div>
        <div class="col-xs-4">
            <?= Html::submitButton('Change', ['class' => 'btn btn-default']) ?>
        </div>
        <?= Html::endForm() ?>
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
