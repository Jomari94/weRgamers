<?php

use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $searchGame app\models\GameSearch */
/* @var $dataProviderGame yii\data\ActiveDataProvider */
/* @var $searchPlatform app\models\PlatformSearch */
/* @var $dataProviderPlatform yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Manage Games');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-index">
    <?= Tabs::widget([
    'items' => [
        [
            'label' => Yii::t('app', 'Games'),
            'content' => $this->render('_index', [
                'searchModel' => $searchGame,
                'dataProvider' => $dataProviderGame,
            ]),
            'active' => true
        ],
        [
            'label' => Yii::t('app', 'Platforms'),
            'content' => $this->render('/platform/_index', [
                'searchModel' => $searchPlatform,
                'dataProvider' => $dataProviderPlatform,
            ]),
        ],

    ],
]); ?>
</div>
