<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Game */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Games'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-view">
    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin): ?>
        <p>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
    <?php endif; ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <section class="col-xs-12 col-md-4 game-side">
        <?= Html::img($model->getCover(), ['class' => 'cover']) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'genre',
                'released:date',
                'developers',
                [
                    'label' => Yii::t('app', 'Platforms'),
                    'value' => $model->getNamePlatforms(),
                ],
            ],
            ]) ?>
    </section>
    <section class="col-xs-12 col-md-8">
        <?php if (Yii::$app->session->hasFlash('published')): ?>
            <p class="alert alert-danger"><?= Yii::$app->session->getFlash('published') ?></p>
        <?php endif; ?>
    <?= $this->render('/reviews/_form', [
        'model' => $review,
        'id_game' => $model->id,
        'id_user' => Yii::$app->user->id,
    ]) ?>
    <?= ListView::widget([
        'dataProvider' => $reviewProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::encode($model->content);
        },
    ]) ?>
    </section>
</div>
