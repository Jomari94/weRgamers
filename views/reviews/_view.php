<?php
use yii\helpers\Html;
?>
<div>
    <input type="text" class="review-scored" value="<?= $model->score ?>" />
</div>
<div>
    <div>
        <div>
            <?= Html::img($model->user->profile->getAvatar(), ['class' => 'img img-circle img64']) ?>
            <?= Html::a($model->user->username, ['/user/profile/show', 'id' => $model->id_user]) ?>
        </div>
        <div>
            <?= Yii::$app->formatter->asDate($model->created, 'dd/MM/Y') ?>
        </div>
    </div>
    <div>
        <?= Html::encode($model->content); ?>
    </div>
</div>
