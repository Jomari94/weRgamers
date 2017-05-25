<?php

use app\assets\JsAsset;
use app\models\Member;
use kartik\datetime\DateTimePicker;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Group */
$dataProvider = new ActiveDataProvider([
    'query' => Member::find()->where(['id_group' => $model->id, 'accepted' => true]),
    'pagination' => [
        'pageSize' => 10,
    ],
]);
JsAsset::register($this);
$inicio = new DateTime($event->inicio);
$inicio = $inicio->format('c');
$options = [
    'inicio' => $inicio,
    'newEvent' => $event->isNewRecord,
    'activity' => Yii::t('app', '{activity} begins in:', ['activity' => $event->activity]),
    'finish' => Yii::t('app', 'The event is on!'),
];
Json::htmlEncode($options);
$this->registerJs(
    "var yiiOptions = ".\yii\helpers\Json::htmlEncode($options).";",
    View::POS_HEAD,
    'yiiOptions'
);
$js = <<<EOT
if (!yiiOptions.newEvent) {
    var begin = moment.tz(yiiOptions.inicio, moment.tz.guess());
    $('#countdown').countdown(begin.toDate(), {})
        .on('update.countdown', function(event) {
            var format = '<input type="text" value="%H" max="24" class="dialh"> <input type="text" value="%M" max="60" class="dialm"> <input type="text" value="%S" max="60" class="dials">';
            if(event.offset.totalDays > 0) {
                format = '<input type="text" value="%-D" max="7" class="diald"> ' + format;
            }
            format = '<p>' + yiiOptions.activity + '</p>' + format;
            $(this).html(event.strftime(format));
            $(".diald").knob({
                'thickness' : .3,
                'width': 110,
                'height': 110,
                'max': 365,
                'readOnly': true,
                'fgColor': '#03fff7',
                'bgColor': '#919191',
                'format': function (value) {
                    return value + ' d';
                }
            });
            $(".dialh").knob({
                'thickness' : .3,
                'width': 110,
                'height': 110,
                'max': 24,
                'readOnly': true,
                'fgColor': '#62ff03',
                'bgColor': '#919191',
                'format': function (value) {
                    return value + ' h';
                }
            });
            $(".dialm").knob({
                'thickness' : .3,
                'width': 110,
                'height': 110,
                'max': 60,
                'readOnly': true,
                'fgColor': '#ffec03',
                'bgColor': '#919191',
                'format': function (value) {
                    return value + ' m';
                }

            });
            $(".dials").knob({
                'thickness' : .3,
                'width': 110,
                'height': 110,
                'max': 60,
                'readOnly': true,
                'fgColor': '#d01616',
                'bgColor': '#919191',
                'format': function (value) {
                    return value + ' s';
                }
            });
        }).on('finish.countdown', function(event) {
            $(this).html(yiiOptions.finish)
            .parent().addClass('disabled');
        });
}
EOT;
$this->registerJs($js);
if (!Yii::$app->user->isGuest) {
    $listener = getenv('LISTENER')?: 'localhost:3000';
    $username = Yii::$app->user->identity->username;
    $avatar = Yii::$app->user->identity->profile->avatar;
    $room = $model->id;
    $jsChat = <<<JS
    var listener = "$listener";
    var username = "$username";
    var avatar = "$avatar";
    var room = "$room";

    var socket = io.connect(listener);
    socket.emit('join', username);
    socket.emit('switchRoom', room);

    $("#message-field").keyup(function(event){
        if(event.keyCode == 13){
            $("#send-button").click();
        }
    });

    $('#send-button').on('click', function(){
        socket.emit('chat message', JSON.stringify({name: username, message: $('#message-field').val()}));
        $('#message-field').val('');
        return false;
    });

    socket.on('chat message', function(msg){
        msg = JSON.parse(msg);
        if (msg.name == username) {
            $('#messages').append($('<div>').html('<img class="img64 img-circle" src="' + avatar + '"><p>' + msg.message + '</p>').addClass('message-view sender'));
        } else {
            var avatarUser = $('#' + msg.name).attr('src');
            $('#messages').append($('<div>').html('<img class="img64 img-circle" src="' + avatarUser + '"><p>' + msg.message + '</p>').addClass('message-view nosender'));
        }
        $("#list").scrollTop($("#list")[0].scrollHeight);
    });

    socket.on('joined', function(msg){
        $('#messages').append($('<div>').text(msg + ' se ha conectado'));
    });

    socket.on('leave', function(msg){
        $('#messages').append($('<div>').text(msg + ' se ha desconectado'));
    });

    $(document).on('ready', function () {
        $('#message-field').focus();
    });
JS;
    $this->registerJs($jsChat, \yii\web\View::POS_READY);
}
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">
    <?php if (Yii::$app->session->hasFlash('Error')): ?>
        <p class="alert alert-danger"><?= Yii::$app->session->getFlash('Error') ?></p>
    <?php endif; ?>

    <h1><?= Html::encode($this->title) ?></h1>
    <header class="row">
        <p class="member-options col-sm-6">
            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) { ?>
                <?= Html::a(Yii::t('app', 'Delete group'), ['groups/delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this group?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php } ?>
            <?php if ($model->isMember(Yii::$app->user->id)) { ?>
                <?= Html::a(Yii::t('app', 'Leave group'), ['members/leave', 'id_group' => $model->id, 'id_user' => Yii::$app->user->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to leave this group?'),
                        'method' => 'post',
                    ],
                ]) ?>
                <?php if ($model->isAdmin(Yii::$app->user->id)) { ?>
                    <?= Html::a(Yii::t('app', 'Members'), ['members/index', 'id_group' => $model->id], ['class' => 'btn btn-primary']) ?>
        			<?php Modal::begin([
        				'header' => '<h3>'.Yii::t('app', 'Event').'</h3>',
        				'toggleButton' => ['label' => Yii::t('app', 'Event'), 'class' => 'btn btn-primary'],
        			]); ?>
                        <?php $form = ActiveForm::begin(); ?>
            			<?= $form->field($event, 'activity')->label(Yii::t('app', 'What are you planning to do?')) ?>
            			<div class="row" style="margin-bottom: 8px">
            				<div class="col-sm-6">
            					<?= $form->field($event, 'inicio')->widget(DateTimePicker::classname(), [
                                	'options' => ['placeholder' => Yii::t('app', 'Start time ...')],
                                	'pluginOptions' => [
                                		'autoclose' => true
                                	]
                                ]); ?>
            				</div>
            				<div class="col-sm-6">
            					<?= $form->field($event, 'fin')->widget(DateTimePicker::classname(), [
                                	'options' => ['placeholder' => Yii::t('app', 'End time ...')],
                                	'pluginOptions' => [
                                		'autoclose' => true
                                	]
                                ]); ?>
            				</div>
                            <div class="form-group col-sm-6">
                                <?= Html::submitButton(Yii::t('app', $event->isNewRecord ? 'Create' : 'Update'), [
                                    'class' => $event->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                                    'name' => 'create',
                                    ]) ?>
                                <?php if (!$event->isNewRecord): ?>
                                    <?= Html::submitButton(Yii::t('app', 'Cancel'), [
                                        'class' => 'btn btn-danger',
                                        'name' => 'cancel',
                                        ]) ?>
                                <?php endif; ?>
                            </div>
            			</div>
                        <?php ActiveForm::end(); ?>
        			<?php Modal::end(); ?>
                <?php } ?>
            <?php } elseif ($model->isPending(Yii::$app->user->id)) { ?>
                <p class="alert alert-success"><?= Yii::t('app', 'Your request is pending of being valued') ?></p>
            <?php } else { ?>
                <?= Html::a(Yii::t('app', 'Join'), ['members/join', 'id_group' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php } ?>
        </p>
    </header>
    <div class="row">
        <div class="col-xs-12 row">
            <div class="col-xs-12 col-sm-4">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => Yii::t('app', 'Game'),
                            'attribute' => 'game.game.name',
                        ],
                        [
                            'label' => Yii::t('app', 'Platform'),
                            'attribute' => 'game.platform.name',
                        ],
                    ],
                    ]) ?>
            </div>
            <div class="col-xs-12 col-sm-8" id="countdown"></div>
        </div>
        <div class="well col-xs-12 row">
            <div class="col-xs-12 col-sm-4">
                <h4><?= Yii::t('app', 'Members') ?></h4>
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemOptions' => [
                        'class' => 'row member-view',
                        'tag' => 'article',
                    ],
                    'options' => [
                        'tag' => 'div',
                        'class' => 'members-wrapper',
                        'id' => 'members-wrapper',
                    ],
                    'layout' => "{items}\n{pager}",
                    'itemView' => '../members/_view',
                    ]) ?>
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="chat" id="list">
                    <?php if (Yii::$app->user->can('viewChat')) { ?>
                        <div id="messages">
                        </div>
                    <?php } else { ?>
                        <p><?= Yii::t('app', "You can't view this chat if you aren't a member") ?></p>
                    <?php } ?>
                </div>
                <?php if (Yii::$app->user->can('viewChat')) { ?>
                <div class="input-group">
                    <?= Html::textInput('message', null, [
                        'id' => 'message-field',
                        'class' => 'form-control',
                        'placeholder' => 'Message',
                        'autocomplete' => 'off',
                    ]) ?>
                    <span class="input-group-btn">
                        <?= Html::buttonInput('Send', [
                            'id' => 'send-button',
                            'class' => 'btn btn-block btn-primary'
                            ]) ?>
                    </span>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>


</div>
