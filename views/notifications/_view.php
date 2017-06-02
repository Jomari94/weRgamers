<?php

use app\models\Notification;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php if ($model->type == Notification::MESSAGE){ ?>
    <span class="glyphicon glyphicon-envelope"></span>
    <span><?= Yii::t('app', '{user} has send you a private message', [
        'user' => Html::a($model->user->username, Url::to(['user/profile/show', 'id' => $model->id_user]))
    ])  ?></span>
<?php } elseif ($model->type == Notification::FOLLOW) {?>
    <span class="glyphicon glyphicon-user"></span>
    <span class="glyphicon glyphicon-thumbs-up"></span>
    <span><?= Yii::t('app', '{user} is now your follower', [
        'user' => Html::a($model->user->username, Url::to(['user/profile/show', 'id' => $model->id_user]))
    ]) ?></span>
<?php } elseif ($model->type == Notification::EVENT) {?>
    <span class="glyphicon glyphicon glyphicon-calendar"></span>
    <span class="glyphicon glyphicon glyphicon-ok"></span>
    <span><?= Yii::t('app', '{user} from {group} has created an event for {inicio}', [
        'user' => Html::a($model->user->username, Url::to(['user/profile/show', 'id' => $model->id_user])),
        'group' => Html::a($model->group->name, Url::to(['groups/view', 'id' => $model->id_group])),
        'inicio' => $model->group->event->inicio
    ]) ?></span>
<?php } elseif ($model->type == Notification::EVENT_CANCELLED) {?>
    <span class="glyphicon glyphicon glyphicon-calendar"></span>
    <span class="glyphicon glyphicon glyphicon-remove"></span>
    <span><?= Yii::t('app', '{user} from {group} has cancelled the event', [
        'user' => Html::a($model->user->username, Url::to(['user/profile/show', 'id' => $model->id_user])),
        'group' => Html::a($model->group->name, Url::to(['groups/view', 'id' => $model->id_group]))
    ]) ?></span>
<?php } elseif ($model->type == Notification::REQUEST) {?>
    <span class="fa fa-users"></span>
    <span class="glyphicon glyphicon-arrow-left"></span>
    <span><?= Yii::t('app', 'There is a join request from {user} for your group {group}', [
        'user' => Html::a($model->user->username, Url::to(['user/profile/show', 'id' => $model->id_user])),
        'group' => Html::a($model->group->name, Url::to(['groups/view', 'id' => $model->id_group]))
    ]) ?></span>
<?php } elseif ($model->type == Notification::CONFIRMATION) {?>
    <span class="fa fa-users"></span>
    <span class="glyphicon glyphicon-ok"></span>
    <span><?= Yii::t('app', "You've been accepted in the group {group}", [
        'group' => Html::a($model->group->name, Url::to(['groups/view', 'id' => $model->id_group]))
        ]) ?></span>
<?php } ?>
