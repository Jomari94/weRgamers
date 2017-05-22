<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Publication */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="publication-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'content')->textarea(['maxlength' => true, 'rows' => 3])->label(false) ?>

    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['accept' => 'audio/mpeg, video/mp4, image/*'],
        'pluginOptions' => [
            'allowedFileExtensions'=>['png', 'jpg', 'gif', 'mp3', 'mp4'],
            'showPreview' => true,
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="fa fa-folder-open"></i> ',
            'browseLabel' =>  Yii::t('app', 'Attach File'),
        ],
    ])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Post'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
