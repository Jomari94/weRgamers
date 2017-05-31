<?php

use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$urlGames = Url::to(['/games/search-ajax']);
$urlPlatforms = Url::to(['/platform/search-ajax']);
$js = <<<EOT
$('#group-game_name').on('focus keyup', function () {
    $.ajax({
        method: 'get',
        url: '$urlGames',
        data: {
            game: $('#group-game_name').val()
        },
        success: function (data, status, event) {
            var juegos = JSON.parse(data);
            $('#group-game_name').autocomplete({source: juegos});
        }
    });
});

$('#group-game_name').on('autocompleteselect', function (event, ui) {
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
        var radio = '<label><input type="radio" name="Group[id_platform]" value="'+ i + '"> '+platforms[i]+'</label> ';
        $('#group-id_platform').append(radio);
    }
}
EOT;
$this->registerJs($js);

$this->title = Yii::t('app', 'Groups');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <p>
            <?php Modal::begin([
                'header' => '<h2>'.Yii::t('app', 'Create Group').'</h2>',
                'toggleButton' => ['label' => Yii::t('app', 'Create Group'), 'class' => 'btn btn-success'],
            ]); ?>
                <?php $form = ActiveForm::begin(['action' => 'create']); ?>
                <div class="group-create-form">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'game_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'id_platform')->radioList([]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            <?php Modal::end(); ?>
        </p>
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-8">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{sorter}\n{items}\n{pager}",
            'itemOptions' => [
                'tag' => 'article',
                'class' => 'group-pview'
            ],
            'itemView' => '_view.php',
            ]) ?>
    </div>

</div>
