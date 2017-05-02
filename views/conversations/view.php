<?php

use kop\y2sp\ScrollPager;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Group */
$js = <<<EOT
var element = document.getElementById("list");
element.scrollTop = element.scrollHeight;
element.scrollTop = element.scrollTop;
EOT;
$this->registerJs($js);

?>
<div class="group-view">
    <div class="list" id="list">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'options' => [
                'tag' => 'div',
                'class' => 'message-wrapper',
                'id' => 'message-wrapper',
            ],
            'layout' => "{items}\n{pager}",
            'itemView' => '../messages/_view.php',
            'pager' => [
                'class' => ScrollPager::className(),
                'container' => '.message-wrapper',
                'triggerText' => Yii::t('app', 'Show old messages'),
                'noneLeftText' => Yii::t('app', 'No messages left'),
                'overflowContainer' => '.list'
            ],
            ]) ?>
    </div>
    <div class="message-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($message, 'content')->textInput()->label(false) ?>
        
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success', 'name' => 'sender' ]) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
