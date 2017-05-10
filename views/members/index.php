<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Members management');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups'), 'url' => ['groups/index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($group->name), 'url' => ['groups/view', 'id' => $group->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-xs-12 col-sm-6">
        <h3><?= Yii::t('app', 'Requests') ?></h3>
        <?= GridView::widget([
            'dataProvider' => $requestProvider,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],

                [
                    'attribute' => 'user.username',
                    'value' => function($model, $key, $index, $column) {
                        return Html::a($model->user->username, ['user/profile/show', 'id' => $model->user->id]);
                    },
                    'format' => 'html',
                ],

                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{accept} {reject}',
                    'buttons' => [
                        'accept' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['confirm', 'id_group' => $model->id_group, 'id_user' => $model->id_user]);
                        },
                        'reject' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['reject', 'id_group' => $model->id_group, 'id_user' => $model->id_user]);
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
    <div class="col-xs-12 col-sm-6">
        <h3><?= Yii::t('app', 'Members') ?></h3>
        <?= GridView::widget([
            'dataProvider' => $memberProvider,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],

                [
                    'attribute' => 'user.username',
                    'value' => function($model, $key, $index, $column) {
                        return Html::a($model->user->username, ['user/profile/show', 'id' => $model->user->id]);
                    },
                    'format' => 'html',
                ],

                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{promote} {ban}',
                    'buttons' => [
                        'ban' => function ($url, $model, $key) {
                            return Html::a(Yii::t('app', 'Ban member'),
                                ['ban', 'id_group' => $model->id_group, 'id_user' => $model->id_user],
                                ['class' => 'btn btn-xs btn-danger', 'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to ban this member?'),
                                    'method' => 'post',
                                ],
                            ]);
                        },
                        'promote' => function ($url, $model, $key) {
                            return Html::a(Yii::t('app', 'Promote member'),
                                ['promote', 'id_group' => $model->id_group, 'id_user' => $model->id_user],
                                ['class' => 'btn btn-xs btn-primary']
                            );
                        },
                    ],
                    'visibleButtons' => [
                        'ban' => function ($model, $key, $index) {
                            return !$model->admin;
                        },
                        'promote' => function ($model, $key, $index) {
                            return !$model->admin;
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
