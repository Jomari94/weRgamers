<?php

use app\models\Notification;
use yii\helpers\Url;
use yii\helpers\Html;
if ($model->group && $model->group->event) {
    $user = Yii::$app->user->identity;
    if ($user && $user->profile->timezone) {
        $tz = new DateTimeZone($user->profile->timezone);
    } else {
        $tz = new DateTimeZone('UTC');
    }
    $iniciotz = new DateTime($model->group->event->inicio, $tz);
    if ($tz = 'Europe/Madrid') {
        $iniciotz = $iniciotz->format('d/m/Y H:i');
    } else {
        $iniciotz = $iniciotz->format('Y-m-d H:i');
    }
} else {
    $iniciotz = null;
}
$urlUser = '/user/profile/show';
$urlGroup = '/groups/view';
?>
<?php if ($model->type == Notification::MESSAGE){ ?>
    <span class="glyphicon glyphicon-envelope"></span>
    <span><?= Yii::t('app', '{user} has send you a private message', [
        'user' => Html::a($model->user->username, Url::to([$urlUser, 'id' => $model->id_user]))
    ])  ?></span>
<?php } elseif ($model->type == Notification::FOLLOW) {?>
    <span class="glyphicon glyphicon-user"></span>
    <span class="glyphicon glyphicon-thumbs-up"></span>
    <span><?= Yii::t('app', '{user} is now your follower', [
        'user' => Html::a($model->user->username, Url::to([$urlUser, 'id' => $model->id_user]))
    ]) ?></span>
<?php } elseif ($model->type == Notification::EVENT) {?>
    <span class="glyphicon glyphicon glyphicon-calendar"></span>
    <span class="glyphicon glyphicon glyphicon-ok"></span>
    <span><?= Yii::t('app', '{user} from {group} has created an event for {inicio}', [
        'user' => Html::a($model->user->username, Url::to([$urlUser, 'id' => $model->id_user])),
        'group' => Html::a($model->group->name, Url::to([$urlGroup, 'id' => $model->id_group])),
        'inicio' => $iniciotz
    ]) ?></span>
<?php } elseif ($model->type == Notification::EVENT_CANCELLED) {?>
    <span class="glyphicon glyphicon glyphicon-calendar"></span>
    <span class="glyphicon glyphicon glyphicon-remove"></span>
    <span><?= Yii::t('app', '{user} from {group} has cancelled the event', [
        'user' => Html::a($model->user->username, Url::to([$urlUser, 'id' => $model->id_user])),
        'group' => Html::a($model->group->name, Url::to([$urlGroup, 'id' => $model->id_group]))
    ]) ?></span>
<?php } elseif ($model->type == Notification::REQUEST) {?>
    <span class="fa fa-users"></span>
    <span class="glyphicon glyphicon-arrow-left"></span>
    <span><?= Yii::t('app', 'There is a join request from {user} for your group {group}', [
        'user' => Html::a($model->user->username, Url::to([$urlUser, 'id' => $model->id_user])),
        'group' => Html::a($model->group->name, Url::to([$urlGroup, 'id' => $model->id_group]))
    ]) ?></span>
<?php } elseif ($model->type == Notification::CONFIRMATION) {?>
    <span class="fa fa-users"></span>
    <span class="glyphicon glyphicon-ok"></span>
    <span><?= Yii::t('app', "You've been accepted in the group {group}", [
        'group' => Html::a($model->group->name, Url::to([$urlGroup, 'id' => $model->id_group]))
        ]) ?></span>
<?php } ?>
