<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\models\Platform;
use app\models\Collection;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\SettingsForm $model
 */

$this->title = Yii::t('user', 'Game collection');
$this->params['breadcrumbs'][] = $this->title;
$add = '/games/addgame';
$drop = '/games/dropgame';
$js = <<<EOT
$('input[name="selection[]"]').on('click', function () {
    if ($(this).is(':checked')) {
        $.ajax({
            url: "$add",
            method: 'POST',
            contentType: 'json',
            data: $(this).val()
        });
    } else {
        $.ajax({
            url: "$drop",
            method: 'POST',
            contentType: 'json',
            data: $(this).val()
        });
    }
});
EOT;
$this->registerJs($js);
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'attribute' => 'name',
                            'value' => 'game.name',
                            'group' => true,
                        ],
                        [
                            'attribute' => 'genre',
                            'value' => 'game.genre',
                            'group' => true,
                        ],
                        [
                            'attribute' => 'developers',
                            'value' => 'game.developers',
                            'group' => true,
                        ],
                        [
                            'attribute' => 'id_platform',
                            'value' => function ($model, $key, $index, $widget) {
                                return $model->platform->name;
                            },
                            'filter' => ArrayHelper::map(Platform::find()->all(), 'id', 'name'),
                            'filterInputOptions' => [
                                'placeholder' => 'Seleccione plataforma',
                                'class' => 'form-control',
                            ],
                        ],
                        [
                            'class' => 'kartik\grid\CheckboxColumn',
                            'checkboxOptions' => function($model, $key, $index, $column) {
                                $id_user = Yii::$app->user->id;
                                $games = Collection::findOne(['id_user' => $id_user, 'id_game' => $model->id_game, 'id_platform' => $model->id_platform]);
                                $bool = $games != null;
                                return ['checked' => $bool];
                            },
                        ],
                    ],
                    'responsive' => true,
                    'hover' => true
                ]); ?>
            </div>
        </div>
    </div>
</div>
