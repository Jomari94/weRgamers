<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Groups');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="menu col-sm-6 col-md-4">
        <p>
            <?= Html::a(Yii::t('app', 'Create Group'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="index col-sm-6 col-md-8">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => [
                'tag' => 'article',
                'class' => 'group-pview'
            ],
            'itemView' => '_view.php',
            ]) ?>
    </div>

</div>
