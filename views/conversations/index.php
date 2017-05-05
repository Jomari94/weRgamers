<?php

use app\assets\AppAsset;
use app\assets\FontAsset;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

AppAsset::register($this);
FontAsset::register($this);
$this->title = Yii::t('app', 'Conversations');
?>
<div class="conversations-index">

    <p>
        <?= Html::a(Yii::t('app', 'New Message'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'conversation-item'],
        'layout' => "{items}\n{pager}",
        'itemView' => '_view.php',
    ]) ?>
</div>
