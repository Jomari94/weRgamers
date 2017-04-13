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
    $('#search').on('focus keyup', function () {
        $.ajax({
            method: 'get',
            url: '$urlGames',
            data: {
                q: $('#search').val()
            },
            success: function (data, status, event) {
                var d = JSON.parse(data);
                games = d;
                d = $.map(d, function(value, index){
                    return index;
                });
                console.log(d);
                $('#search').autocomplete({source: d});
            }
        });
    });

    $('#search').on('autocompleteselect', function (event, ui) {
        $('#group-id_game').val(games[ui.item.value]);
        console.log(ui.item);
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
        console.log(platforms);
        $('#platforms').empty();
        var label = '<label for="platforms">Select platform</label><br />'
        $('#platforms').append(label);
        for(i in platforms) {
            var radio = '<input type="radio" name="platform" value="'+ i + '">'+platforms[i]+'<br />';
            $('#platforms').append(radio);
        }
        $('input[type="radio"]').on('click', function (){
            $('#group-id_platform').val($(this).val());
        });
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

        <label for="search"><?= Yii::t('app', 'Game search') ?></label>
        <input type="text" id="search" class="form-control" placeholder="<?= Yii::t('app', 'Search') ?>">

        <?= $form->field($model, 'id_game')->hiddenInput()->label(false) ?>

        <div id="platforms">
        </div>

        <?= $form->field($model, 'id_platform')->hiddenInput()->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
