<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GroupSearch */
/* @var $form yii\widgets\ActiveForm */

$urlGames = Url::to(['/games/search-ajax']);
$urlPlatforms = Url::to(['/platform/search-ajax']);
$js = <<<EOT
$('#groupsearch-game_name').on('focus keyup', function () {
    $.ajax({
        method: 'get',
        url: '$urlGames',
        data: {
            game: $('#groupsearch-game_name').val()
        },
        success: function (data, status, event) {
            var juegos = JSON.parse(data);
            $('#groupsearch-game_name').autocomplete({source: juegos});
        }
    });
});
EOT;
$this->registerJs($js);
?>

<div class="group-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'game_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'myGroups')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
