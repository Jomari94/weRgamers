<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\models\Vote;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \dektrium\user\models\Profile $profile
 */
$urlFollow = Url::to(['/followers/follow']);
$urlUnfollow = Url::to(['/followers/unfollow']);
$urlVote = Url::to(['/votes/vote']);
$user_id = $profile->user_id;
$toolbar = Url::to(['/user/profile/show?id='.$profile->user_id]). ' #toolbar';
$js = <<<EOT
cargaBotones();

function cargaBotones(datos, status, xhr) {
    $('#column').load("$toolbar", function(){
        $('.btn-follow').on('click', function () {
            $.ajax({
                url: "$urlFollow",
                method: 'POST',
                data: {'followed_id': $user_id},
                success: cargaBotones
            });
        });

        $('.btn-unfollow').on('click', function () {
            $.ajax({
                url: "$urlUnfollow",
                method: 'POST',
                data: {'followed_id': $user_id},
                success: cargaBotones
            });
        });

        $('#up').on('click', function () {
            $.ajax({
                url: "$urlVote",
                method: 'POST',
                data: {'voted_id': $user_id, 'positive': 1},
                success: cargaBotones
            });
        });

        $('#down').on('click', function () {
            $.ajax({
                url: "$urlVote",
                method: 'POST',
                data: {'voted_id': $user_id, 'positive': 0},
                success: cargaBotones
            });
        });

        $('.btn-unfollow').hover(function (){
            $(this).text('Dejar de seguir');
        }, function (){
            $(this).html('<span class="glyphicon glyphicon-ok"></span> Siguiendo');
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
            <div class="col-sm-6 col-md-7" id="column">
                <?php if (Yii::$app->user->isGuest || Yii::$app->user->id !== $profile->user_id){ ?>
                <div class="toolbar" id="toolbar">
                    <div class="btn-group" id="buttons">
                        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isFollower($profile->user_id)){ ?>
                            <button id="btn-follow" class="btn btn-danger btn-unfollow"><span class="glyphicon glyphicon-ok"></span> Siguiendo</button>
                        <?php } else { ?>
                            <button id="btn-follow" class="btn btn-default btn-follow"><span class="glyphicon glyphicon-user"></span> Seguir</button>
                        <?php } ?>
                    </div>
                    <div class="btn-group" id="#votes">
                        <button class="btn
                        <?= !Yii::$app->user->isGuest &&
                            Yii::$app->user->identity->hasVoted($profile->user_id) &&
                            Vote::findOne(['id_voter' => Yii::$app->user->id, 'id_voted' => $profile->user_id])->positive ? 'btn-danger' : 'btn-default'?>" id="up"><span class="glyphicon glyphicon-thumbs-up"></span></button>
                        <button class="btn
                        <?= !Yii::$app->user->isGuest &&
                            Yii::$app->user->identity->hasVoted($profile->user_id) &&
                            Vote::findOne(['id_voter' => Yii::$app->user->id, 'id_voted' => $profile->user_id])->positive == false ? 'btn-danger' : 'btn-default'?>" id="down"><span class="glyphicon glyphicon-thumbs-down"></span></button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
