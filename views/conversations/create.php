<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Group */

$url = Url::to(['/conversations/search-users']);
$js = <<<EOT
    $('#conversation-username').on('focus keyup', function () {
        $.ajax({
            method: 'get',
            url: '$url',
            data: {
                name: $('#conversation-username').val()
            },
            success: function (data, status, event) {
                var usuarios = JSON.parse(data);
                console.log(usuarios);
                $('#conversation-username').autocomplete({source: usuarios});
            }
        });
    });
EOT;
$this->registerJs($js);

$this->title = Yii::t('app', 'Find user');
?>
<div class="group-create">
    <?= Html::a(Yii::t('app', 'Back to conversations'), Url::to(['conversations/index']), ['class' => 'btn btn-primary']) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="conversation-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Send message'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
