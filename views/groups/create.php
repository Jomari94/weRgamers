<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Group */

$urlGames = Url::to(['/games/search-ajax']);
$urlPlatforms = Url::to(['/games/platforms-ajax']);
$js = <<<EOT
var games = {};
    $('#group-game_name').on('focus keyup', function () {
        $.ajax({
            method: 'get',
            url: '$urlGames',
            data: {
                q: $('#group-game_name').val()
            },
            success: function (data, status, event) {
                var juegos = JSON.parse(data);
                games = juegos;
                juegos = $.map(juegos, function(value, index){
                    return index;
                });;
                $('#group-game_name').autocomplete({source: juegos});
            }
        });
    });

    $('#group-game_name').on('autocompleteselect', function (event, ui) {
        $('#group-id_game').val(games[ui.item.value]);
        $.ajax({
            method: 'get',
            url: '$urlPlatforms',
            data: {
                name: ui.item.value
            },
            success: mostrarPlataformas
        });
    });


    function mostrarPlataformas(data, status, event) {
        var platforms = JSON.parse(data);
        $('#group-id_platform').empty();
        for(i in platforms) {
            var radio = '<label><input type="radio" name="Group[id_platform]" value="'+ i + '"> '+platforms[i]+'</label>';
            $('#group-id_platform').append(radio);
        }
    }
EOT;
$this->registerJs($js);

$this->title = Yii::t('app', 'Create Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="group-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'game_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_game')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'id_platform')->radioList([]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
