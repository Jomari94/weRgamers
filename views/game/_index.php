<?php

use app\models\Platform;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Games');
?>
<div class="game-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Add Game'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'genre',
            'released:date',
            'developers',
            [
                'label' => Yii::t('app', 'Platforms'),
                'value' => function ($model)
                {
                    return implode(', ', $model->getNamePlatforms());
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp{update}',
            ],
        ],
    ]); ?>
</div>
