<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Game */

$this->title = Yii::t('app', 'Add Game');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Games'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'platforms' => $platforms
    ]) ?>

</div>
