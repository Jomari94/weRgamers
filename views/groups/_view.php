<?php

use yii\helpers\Html;
?>

<h4>
    <?= Html::a(Html::encode($model->name), ['groups/view', 'id' => $model->id]);?>
</h4>
<p>
    <?= $model->totalMembers . ' ' . ($model->totalMembers == 1 ? Yii::t('app', 'member') : Yii::t('app', 'members')) ?>
</p>
<?= $model->game->game->name . ' - ' . $model->game->platform->name ?>
