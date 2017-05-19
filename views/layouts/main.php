<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\JsAsset;
use app\assets\FontAsset;
use app\models\Notification;
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

$url = Url::to(['/conversations/index']);
$urlN = Url::to(['/site/notificated']);
$urlUsers = Url::to(['/site/bloodhound-users']) . '?q=%QUERY';
$urlGames = Url::to(['/site/bloodhound-games']) . '?q=%QUERY';
$userLink = Url::to(['/user/profile/show']) . '?id=';
$gameLink = Url::to(['/games/view']) . '?id=';
$js = <<<EOT
$('#messages-link').on('click', function () {
    var ventana = open("$url", "ventana", "width=600,height=640,toolbar=0,titlebar=0");
});

$('#notifications-link').on('click', function () {
    $("#modal").modal("show");
    $.ajax({
        method: 'post',
        url: '$urlN',
    });
});

var users = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
    url: "$urlUsers",
    wildcard: '%QUERY'
  }
});

var games = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
    url: "$urlGames",
    wildcard: '%QUERY'
  }
});

$('.typeahead').typeahead({
    hint: true,
    minLength: 1,
}, {
    name: 'users',
    source: users,
     displayKey: 'username',
    templates: {
        header: '<h4 class="name">Users</h4>',
        // pending: '<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>',
        suggestion: function(data) {
            html = '<div class="media">';
            html += '<div class="media-left"><a class="pull-left" href ="' + "$userLink" + data.id + '"><img class="img-suggestion img-rounded media-object" src=' + data.avatar + ' /></a></div>'
            html += '<div class="media-body">';
            html += '<p class="media-heading"><a href ="' + "$userLink" + data.id + '">' + data.username + '</a></p>';
            html += '<div class="user-search-view row"><span class="col-xs-5">' + data.karma + ' Karma</span><span class="col-xs-7">' + data.followers + ' Followers</span></div>';
            html += '</div></div>';
            return html;
        }
    }
}, {
    name: 'games',
    source: games,
    displayKey: 'name',
    templates: {
        header: '<h4 class="name">Games</h4>',
        suggestion: function(data) {
            html = '<div class="media">';
            html += '<div class="media-left"><a class="pull-left" href ="' + "$gameLink" + data.id + '"><img class="img-suggestion media-object" src=' + data.cover + ' /></a></div>'
            html += '<div class="media-body">';
            html += '<p class="media-heading"><a href ="' + "$gameLink" + data.id + '">' + data.name + '</a></p>';
            html += '<div class="user-search-view row"><span class="col-xs-5">Score: ' + data.score + '</span><span class="col-xs-7">' + data.reviews + ' Reviews</span></div>';
            html += '</div></div>';
            return html;
        }
    }
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
            '<form class="navbar-form navbar-left" method="POST" action="'.Url::to(['/site/search']).'">
                <div class="form-group search-form">
                    <input type="text" class="form-control typeahead" placeholder="Search">
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
