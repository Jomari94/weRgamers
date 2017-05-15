<?php

use app\models\Platform;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="game-index">

    <h1><?= Html::encode(Yii::t('app', 'Games')) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Add Game'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'attribute' => 'name',
            'genre',
            'released:date',
            'developers',
            [
                'label' => Yii::t('app', 'Platforms'),
                'attribute' => 'namePlatforms',
                'filter' => ArrayHelper::map(Platform::find()->all(), 'name', 'name'),
                'filterInputOptions' => [
                    'class' => 'form-control',
                ],
            ],

            ['class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['view', 'id' => $model->id]));
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['update', 'id' => $model->id]));
                    },
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
    ]); ?>
</div>
