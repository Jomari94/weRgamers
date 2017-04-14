<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GroupSearch */
/* @var $form yii\widgets\ActiveForm */

$urlGames = Url::to(['/games/search-ajax']);
$urlPlatforms = Url::to(['/games/platforms-ajax']);
$js = <<<EOT
var games = {};
    $('#groupsearch-game_name').on('focus keyup', function () {
        $.ajax({
            method: 'get',
            url: '$urlGames',
            data: {
                q: $('#groupsearch-game_name').val()
            },
            success: function (data, status, event) {
                var juegos = JSON.parse(data);
                games = juegos;
                juegos = $.map(juegos, function(value, index){
                    return index;
                });;
                $('#groupsearch-game_name').autocomplete({source: juegos});
            }
        });
    });

    $('#groupsearch-game_name').on('autocompleteselect', function (event, ui) {
        $('#groupsearch-id_game').val(games[ui.item.value]);
        // $.ajax({
        //     method: 'get',
        //     url: '$urlPlatforms',
        //     data: {
        //         name: ui.item.value
        //     },
        //     success: mostrarPlataformas
        // });
    });


    // function mostrarPlataformas(data, status, event) {
    //     var platforms = JSON.parse(data);
    //     $('#groupsearch-id_platform').empty();
    //     for(i in platforms) {
    //         var radio = '<label><input type="radio" name="Group[id_platform]" value="'+ i + '"> '+platforms[i]+'</label>';
    //         $('#groupsearch-id_platform').append(radio);
    //     }
    // }
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

    <?= $form->field($model, 'id_game')->hiddenInput()->label(false) ?>

    <!-- <?= $form->field($model, 'id_platform')->radioList([]) ?> -->

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
