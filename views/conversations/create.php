<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Group */

$url = Url::to(['/conversations/search-users']);
$urlPlatforms = Url::to(['/games/platforms-ajax']);
$js = <<<EOT
var users = {};
    $('#conversation-username').on('focus keyup', function () {
        $.ajax({
            method: 'get',
            url: '$url',
            data: {
                q: $('#conversation-username').val()
            },
            success: function (data, status, event) {
                var usuarios = JSON.parse(data);
                users = usuarios;
                usuarios = $.map(usuarios, function(value, index){
                    return index;
                });;
                $('#conversation-username').autocomplete({source: usuarios});
            }
        });
    });

    $('#conversation-username').on('autocompleteselect', function (event, ui) {
        $('#conversation-id_participant2').val(users[ui.item.value]);
    });
EOT;
$this->registerJs($js);

$this->title = Yii::t('app', 'Find user');
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="conversation-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_participant2')->hiddenInput()->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Send message'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
