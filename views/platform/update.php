<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Platform */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app', 'Platform'),
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Platforms'), 'url' => ['/games/index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="platform-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
