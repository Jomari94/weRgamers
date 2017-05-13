<?php

use yii\helpers\Html;
?>

<?= Html::a(Html::encode($model->name), ['view', 'id' => $model->id]); ?>
