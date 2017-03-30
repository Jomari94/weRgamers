<?php

use app\models\Platform;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlatformSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$js = <<<EOT
    $(document).ready(function() {
       $('a[data-toggle=\"tab\"]').on('show.bs.tab', function (e) {
          localStorage.setItem('lastTab', $(this).attr('href'));
       });
       var lastTab = localStorage.getItem('lastTab');
       if (lastTab) {
          $('[href=\"' + lastTab + '\"]').tab('show');
       }
    });
EOT;
$this->registerJs($js);
?>
<div class="platform-index">

    <h1><?= Html::encode(Yii::t('app', 'Platforms')) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Add Platform'), ['platform/create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'filter' => ArrayHelper::map(Platform::find()->all(), 'name', 'name'),
            ],

            ['class' => 'kartik\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        $url = Url::to(['platform/update', 'id' => $model->id]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'update'),
                    ]);
                },
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
    ]); ?>
</div>
