<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pending requests');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'user.username',

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
