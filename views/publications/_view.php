<?php
use yii\helpers\Html;
?>

<?= Html::img($model->user->profile->avatar, ['class' => 'img64 img-rounded']) ?>
<div>
    <p><?= Html::a($model->user->username, ['profile/show', 'id' => $model->user->id]) ?> <?= Yii::$app->formatter->asRelativeTime($model->created) ?></p>
    <p><?= $model->content ?></p>
</div>
