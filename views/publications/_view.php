<?php
use yii\helpers\Html;
?>

<?= Html::img($model->user->profile->avatar, ['class' => 'img64 img-rounded']) ?>
<div>
    <h5><?= Html::a($model->user->username, ['profile/show', 'id' => $model->user->id]) ?> <?= Yii::$app->formatter->asRelativeTime($model->created) ?></h5>
    <p><?= $model->content ?></p>
    <?php if ($model->attachment !== null) {
        if ($model->attachmentType == 'mp3') { ?>
            <audio controls='controls' preload='auto'>
                <source src="<?= $model->attachment ?>" type='audio/mpeg' />
                <object data="<?= $model->attachment ?>"></object>
            </audio>
        <?php } elseif ($model->attachmentType == 'png' || $model->attachmentType == 'jpg' || $model->attachmentType == 'gif') { ?>
            <?= Html::img($model->attachment, ['class' => 'img img-responsive']) ?>
        <?php } elseif ($model->attachmentType == 'mp4') { ?>
            <video controls="controls">
                <source src="<?= $model->attachment ?>" type="video/mp4">
                <object type="video/mp4" data="<?= $model->attachment ?>" >
                <embed type="video/mp4" src="video/mp4" flashvars="mp4=<?= $model->attachment ?>" />
                </object>
            </video>
        <?php } else { ?>
            <br />
        <?php }
    } ?>
</div>
