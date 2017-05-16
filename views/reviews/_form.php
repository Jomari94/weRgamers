<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Review */
/* @var $form yii\widgets\ActiveForm */
$js = <<<EOT
$('#review-score').knob({
    'min': 0,
    'max': 10,
    'width': 100,
    'height': 100,
    'fgColor': '#d01616',
    'change': function (v) {
        if (v <= 4) {
            $('#review-score').trigger(
                'configure',
                {
                    'fgColor': '#d01616',
                    'inputColor': '#d01616'
                }
            );
        }
        if (v >= 5 && v <=7) {
            $('#review-score').trigger(
                'configure',
                {
                    'fgColor': '#fea000',
                    'inputColor': '#fea000'
                }
            );
        }
        if (v > 7) {
            $('#review-score').trigger(
                'configure',
                {
                    'fgColor': '#62ff03',
                    'inputColor': '#62ff03'
                }
            );
        }
    }
});
EOT;
$this->registerJs($js);
$model->id_user = $id_user;
$model->id_game = $id_game;
?>

<div class="review-form">
    <?php $form = ActiveForm::begin([
        'action' => ['/reviews/create'],
    ]); ?>
    <div class="review-form-flex">
        <div class="form-group field-review-score">
            <label for="review-score"><?= Yii::t('app', 'Score') ?></label>
            <input type="text" id="review-score" name="Review[score]" value="0" />
        </div>

        <?= $form->field($model, 'content', ['inputOptions' => ['autocomplete'=>'off', 'class' => 'form-control']])->textarea(['rows' => 4]) ?>
    </div>
    
    <?= $form->field($model, 'id_game')->hiddenInput(['value' => $id_game])->label(false) ?>

    <?= $form->field($model, 'id_user')->hiddenInput(['value' => $id_user])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
