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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Add Game'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'value' => 'game.name',
                'group' => true,
            ],
            [
                'attribute' => 'genre',
                'value' => 'game.genre',
                'group' => true,
            ],
            [
                'attribute' => 'released',
                'value' => 'game.released',
                'format' => 'date',
                'group' => true,
            ],
            [
                'attribute' => 'developers',
                'value' => 'game.developers',
                'group' => true,
            ],
            [
                'attribute' => 'id_platform',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->platform->name;
                },
                'filter' => ArrayHelper::map(Platform::find()->all(), 'id', 'name'),
                'filterInputOptions' => [
                    'placeholder' => 'Seleccione plataforma',
                    'class' => 'form-control',
                ],
            ],

            ['class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['view', 'id' => $model->id_game]));
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['update', 'id' => $model->id_game]));
                    },
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true
    ]); ?>
</div>
