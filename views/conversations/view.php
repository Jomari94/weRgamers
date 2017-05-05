<?php

use kop\y2sp\ScrollPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Group */
$js = <<<EOT
var element = document.getElementById("list");
element.scrollTop = element.scrollHeight;
element.scrollTop = element.scrollTop;
$(document).on('ready', function () {
    $('.field-message-content #message-content').focus();
});
EOT;
$this->registerJs($js);
$templateMessage = '{label}<div class="input-group">{input}
<span class="input-group-btn">
    <button type="submit" class="btn btn-primary" name="sender">Enviar</button>
</span></div>{hint}{error}';

?>
<?= Html::a(Yii::t('app', 'Back to conversations'), Url::to(['conversations/index']), ['class' => 'btn btn-primary']) ?>
<h3><?=Html::img($receiver->profile->getAvatar(), ['class' => 'img-rounded img64'])?> <?= $receiver->username ?></h3>
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
            'noneLeftText' => '',
            'overflowContainer' => '.list'
        ],
        ]) ?>
</div>
<div class="message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($message, 'content', ['template' => $templateMessage])->textInput()->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
