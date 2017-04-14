<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Game */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="game-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],

    ]); ?>
    <div class="col-lg-5">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'genre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'released')->
        widget(DatePicker::className(),[
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'yearRange' => '-115:+0',
                'changeYear' => true
            ],
            'options' => ['class' => 'form-control']
        ]) ?>

    <?= $form->field($model, 'developers')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'platforms')->checkboxList($platforms) ?>

    </div>
    <div class="col-lg-offset-1 col-lg-6">
    <?= $form->field($model, 'imageFile')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'allowedFileExtensions'=>['png', 'jpg', 'gif'],
            'showPreview' => true,
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-picture"></i> ',
            'browseLabel' =>  Yii::t('app', 'Select Cover'),
            'initialPreview' => ($model->getCover() == '/covers/default.png' ? [] : [$model->getCover()]),
            'initialPreviewAsData'=>true,
        ],
    ]) ?>
    </div>

    <div class="form-group col-lg-12">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
