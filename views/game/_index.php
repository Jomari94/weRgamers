<?php

use app\models\Platform;
use yii\helpers\Html;
use yii\grid\GridView;
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
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'genre',
            'released:date',
            'developers',
            [
                'label' => Yii::t('app', 'Platforms'),
                'attribute' => 'namePlatforms',
                'value' => function ($model)
                {
                    return implode(', ', $model->getNamePlatforms());
                },
                'filter' => ArrayHelper::map(Platform::find()->all(), 'name', 'name'),
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp{update}',
            ],
        ],
    ]); ?>
</div>
