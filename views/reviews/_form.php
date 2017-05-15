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
    'width': 80,
    'height': 80,
    'fgColor': '#d03939',
    'change': function (v) {
        if (v <= 4) {
            $('#review-score').trigger(
                'configure',
                {
                    'fgColor': '#d03939',
                    'inputColor': '#d03939'
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

    <!-- <?= $form->field($model, 'score')->textInput(['value' => 0]) ?> -->

    <?= $form->field($model, 'id_user')->hiddenInput(['value' => $id_user])->label(false) ?>

    <?= $form->field($model, 'id_game')->hiddenInput(['value' => $id_game])->label(false) ?>

    <input type="text" id="review-score" name="Review[score]" value="0" />
    <label for="review-score"><?= Yii::t('app', 'Score') ?></label>

    <?= $form->field($model, 'content', ['inputOptions' => ['autocomplete'=>'off', 'class' => 'form-control']])->textarea(['rows' => 4]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
