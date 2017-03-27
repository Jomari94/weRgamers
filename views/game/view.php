<?php

use app\models\Platform;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Game */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Games'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'genre',
            'released',
            'developers',
            [
                'label' => Yii::t('app', 'Platforms'),
                'value' => implode(', ', $model->getNamePlatforms()),
            ],
            [
                'label' => Yii::t('app', 'Cover'),
                'value' => Html::img($model->getCover()),
                'format' => 'html'
            ]
        ],
    ]) ?>

</div>
