<?php

use yii\helpers\Html;
?>

<h2>
    <?= Html::a(Html::encode($model->name), ['groups/view', 'id' => $model->id]);?>
</h2>
<p>
    <?= $model->totalMembers . ' ' . ($model->totalMembers == 1 ? Yii::t('app', 'member') : Yii::t('app', 'members')) ?>
</p>
<?= $model->game->game->name . ' - ' . $model->game->platform->name ?>
