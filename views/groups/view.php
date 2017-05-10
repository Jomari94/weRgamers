<?php

use app\models\Member;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Group */
$dataProvider = new ActiveDataProvider([
    'query' => Member::find()->where(['id_group' => $model->id, 'accepted' => true]),
    'pagination' => [
        'pageSize' => 10,
    ],
]);
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">
    <?php if (Yii::$app->session->hasFlash('Error')): ?>
        <p class="alert alert-danger"><?= Yii::$app->session->getFlash('Error') ?></p>
    <?php endif; ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="member-options">
        <?php if ($model->isMember(Yii::$app->user->id)) { ?>
            <?= Html::a(Yii::t('app', 'Leave group'), ['members/leave', 'id_group' => $model->id, 'id_user' => Yii::$app->user->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to leave this group?'),
                    'method' => 'post',
                ],
            ]) ?>
            <?php if ($model->isAdmin(Yii::$app->user->id)) { ?>
                <?= Html::a(Yii::t('app', 'Members'), ['members/index', 'id_group' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php } ?>
        <?php } elseif ($model->isPending(Yii::$app->user->id)) { ?>
            <p class="alert alert-success"><?= Yii::t('app', 'Your request is pending of being valued') ?></p>
        <?php } else { ?>
            <?= Html::a(Yii::t('app', 'Join'), ['members/join', 'id_group' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php } ?>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) { ?>
            <?= Html::a(Yii::t('app', 'Delete group'), ['groups/delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this group?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php } ?>
    </p>
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <h4><?= Yii::t('app', 'Members') ?></h4>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => [
                    'class' => 'row member-view',
                    'tag' => 'article',
                ],
                'options' => [
                    'tag' => 'div',
                    'class' => 'members-wrapper',
                    'id' => 'members-wrapper',
                ],
                'layout' => "{items}\n{pager}",
                'itemView' => '../members/_view',
            ]) ?>
        </div>
        <div class="col-xs-12 col-sm-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    [
                        'label' => Yii::t('app', 'Game'),
                        'attribute' => 'game.game.name',
                    ],
                    [
                        'label' => Yii::t('app', 'Platform'),
                        'attribute' => 'game.platform.name',
                    ],
                ],
                ]) ?>
        </div>
    </div>


</div>
