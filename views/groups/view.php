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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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

    <?php if ($model->isMember(Yii::$app->user->id)) { ?>
        <div class="member-options">
            <?= Html::a(Yii::t('app', 'Leave group'), ['members/leave', 'id_group' => $model->id, 'id_user' => Yii::$app->user->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to leave this group?'),
                'method' => 'post',
            ],
        ]) ?>
        </div>
    <?php } ?>

</div>
