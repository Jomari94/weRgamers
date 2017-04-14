<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Group */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">
    <?php if (Yii::$app->session->hasFlash('Pending')): ?>
        <p class="alert alert-success"><?= Yii::$app->session->getFlash('Pending') ?></p>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('Error')): ?>
        <p class="alert alert-danger"><?= Yii::$app->session->getFlash('Error') ?></p>
    <?php endif; ?>
    <div class="flash">

    </div>

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
            <?= Html::a(Yii::t('app', 'Requests'), ['members/requests', 'id_group' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php } elseif ($model->isPending(Yii::$app->user->id)) { ?>
            <p class="alert alert-success"><?= Yii::t('app', 'Your request is pending of being valued') ?></p>
        <?php } else { ?>
            <?= Html::a(Yii::t('app', 'Join'), ['members/join', 'id_group' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'label' => 'Game',
                'attribute' => 'game.game.name',
            ],
            [
                'label' => 'Platform',
                'attribute' => 'game.platform.name',
            ],
        ],
    ]) ?>


</div>
