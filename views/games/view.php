<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Game */

$js = <<<EOT
$('.review-scored').knob({
    'thickness': .1,
    'min': 0,
    'max': 10,
    'width': 80,
    'height': 80,
    'readOnly': true
});
$('.total-scored').knob({
    'thickness': .1,
    'step': .1,
    'min': 0,
    'max': 10,
    'width': 80,
    'height': 80,
    'readOnly': true
});
if ($('.review-scored, .total-scored').val() <= 4) {
    $('.review-scored, .total-scored').trigger(
        'configure',
        {
            'fgColor': '#d01616',
            'inputColor': '#d01616'
        }
    );
}
if ($('.review-scored, .total-scored').val() >= 5 && $('.review-scored, .total-scored').val() <=7) {
    $('.review-scored, .total-scored').trigger(
        'configure',
        {
            'fgColor': '#fea000',
            'inputColor': '#fea000'
        }
    );
}
if ($('.review-scored, .total-scored').val() > 7) {
    $('.review-scored, .total-scored').trigger(
        'configure',
        {
            'fgColor': '#62ff03',
            'inputColor': '#62ff03'
        }
    );
}
EOT;
$this->registerJs($js);
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Games'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-view" itemscope itemtype="http://schema.org/VideoGame">
    <span itemprop="applicationCategory" hidden>Game</span>
    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin): ?>
        <p>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
    <?php endif; ?>
    <h1 itemprop="name"><?= Html::encode($this->title) ?></h1>
    <section class="col-xs-12 col-md-4 game-side">
        <?= Html::img($model->getCover(), ['class' => 'cover', 'itemprop' => 'image', 'alt' => $model->name]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => Yii::t('app', 'Genre'),
                    'attribute' => 'genre',
                    'contentOptions' => ['itemprop' => 'genre'],
                ],
                [
                    'label' => Yii::t('app', 'Released'),
                    'attribute' => 'released',
                    'format' => 'date',
                    'contentOptions' => ['itemprop' => 'datePublished'],
                ],
                [
                    'label' => Yii::t('app', 'Developers'),
                    'attribute' => 'developers',
                    'value' => '<span itemprop="name">'.$model->developers.'</span>',
                    'format' => 'raw',
                    'contentOptions' => [
                        'itemprop' => 'creator',
                        'itemscope' => '',
                        'itemtype' => 'http://schema.org/Organization'
                    ],
                ],
                [
                    'label' => Yii::t('app', 'Platforms'),
                    'value' => $model->getNamePlatforms(),
                    'contentOptions' => ['itemprop' => 'gamePlatform'],
                ],
            ],
            ]) ?>
    </section>
    <section class="col-xs-12 col-md-8" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <p id="total-score">
            <input type="text" class="total-scored" itemprop="ratingValue" value="<?= $model->score ?>" /> <?= Yii::t('app', 'based on') ?> <span itemprop="ratingCount"><?= $model->totalReviews ?></span> <?= Yii::t('app', 'reviews') ?>
        </p>
        <br />
        <h2><?= Yii::t('app', 'Review this game') ?></h2>
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
            'layout' => "{items}\n{pager}",
            'itemOptions' => [
                'tag' => 'article',
                'class' => 'reviewp-view',
            ],
            'options' => [
                'id' => 'reviews-wrapper',
                'class' => 'reviews-wrapper',
            ],
            'itemView' => '/reviews/_view',
        ]) ?>
    </section>
</div>
