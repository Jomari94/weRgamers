<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \dektrium\user\models\Profile $profile
 */
$follow = Url::to(['/followers/follow']);
$unfollow = Url::to(['/followers/unfollow']);
// $data = ['followed_id' => $profile->user_id];
// $data = Json::encode($data);
$data = $profile->user_id;
$button = Url::to(['/user/profile/show?id='.$profile->user_id]). ' #btn-follow';
$js = <<<EOT
$('.btn-follow').on('click',function () {
    $.ajax({
        url: "$follow",
        method: 'POST',
        data: {'followed_id': $data},
        success: cambiaBoton
    });
});

$('.btn-unfollow').on('click',function () {
    $.ajax({
        url: "$unfollow",
        method: 'POST',
        data: {'followed_id': $data},
        success: cambiaBoton
    });
});

$('.btn-unfollow').hover(function (){
    $(this).text('Dejar de seguir');
}, function (){
    $(this).text('Siguiendo');
});

function cambiaBoton(datos, status, xhr) {
    $('#buttons').load("$button", function(){
        $('.btn-follow').on('click',function () {
            $.ajax({
                url: "$follow",
                method: 'POST',
                data: {'followed_id': $data},
                success: cambiaBoton
            });
        });

        $('.btn-unfollow').on('click',function () {
            $.ajax({
                url: "$unfollow",
                method: 'POST',
                data: {'followed_id': $data},
                success: cambiaBoton
            });
        });

        $('.btn-unfollow').hover(function (){
            $(this).text('Dejar de seguir');
        }, function (){
            $(this).text('Siguiendo');
        });
    });

}
EOT;
$this->registerJs($js);
$this->title = empty($profile->name) ? Html::encode($profile->user->username) : Html::encode($profile->name);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="row">
            <div class="col-sm-6 col-md-5">
                <?= Html::img($profile->getAvatar(), [
                    'class' => 'img-rounded img-responsive',
                    'alt' => $profile->user->username,
                ]) ?>
                <h4><?= $this->title ?></h4>
                <ul style="padding: 0; list-style: none outside none;">
                    <?php if (!empty($profile->location)): ?>
                        <li>
                            <i class="glyphicon glyphicon-map-marker text-muted"></i> <?= Html::encode($profile->location) ?>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($profile->website)): ?>
                        <li>
                            <i class="glyphicon glyphicon-globe text-muted"></i> <?= Html::a(Html::encode($profile->website), Html::encode($profile->website)) ?>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($profile->public_email)): ?>
                        <li>
                            <i class="glyphicon glyphicon-envelope text-muted"></i> <?= Html::a(Html::encode($profile->public_email), 'mailto:' . Html::encode($profile->public_email)) ?>
                        </li>
                    <?php endif; ?>
                    <li>
                        <i class="glyphicon glyphicon-time text-muted"></i> <?= Yii::t('user', 'Joined on {0, date}', $profile->user->created_at) ?>
                    </li>
                </ul>
                <?php if (!empty($profile->bio)): ?>
                    <p><?= Html::encode($profile->bio) ?></p>
                <?php endif; ?>
            </div>
            <div class="col-sm-6 col-md-7">
                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id !== $profile->user_id){ ?>
                <div id="buttons">
                    <?php if (Yii::$app->user->identity->isFollower($profile->user_id)){ ?>
                        <button id="btn-follow" class="btn btn-danger btn-unfollow">Siguiendo</button>
                    <?php } else { ?>
                        <button id="btn-follow" class="btn btn-primary btn-follow">Seguir</button>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
