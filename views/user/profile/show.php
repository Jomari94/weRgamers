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
use kop\y2sp\ScrollPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;

/**
 * @var \yii\web\View $this
 * @var \dektrium\user\models\Profile $profile
 */
$urlFollow = Url::to(['/followers/follow']);
$urlUnfollow = Url::to(['/followers/unfollow']);
$urlVote = Url::to(['/votes/vote']);
$toolbar = Url::to(['/user/profile/show?id='.$profile->user_id]). ' #toolbar';


$js = <<<EOT
$(document).ready(function(){
    if(!$('#publications-list').is(':visible') && !$('#followers-list').is(':visible') && !$('#followings-list').is(':visible')) {
        document.getElementById('link-publications-list').click();
    }
    scrollTo(0,0);

    cargaBotones();
});

$('[href*="#"]').click(function(event) {
    scrollTo(0,0);
});


function cargaBotones(datos, status, xhr) {
    $('#column').load("$toolbar", function(){
        $('.btn-follow').on('click', function () {
            $.ajax({
                url: "$urlFollow",
                method: 'POST',
                data: {'followed_id': $profile->user_id},
                success: function (datos, status, xhr) {
                    $('#followers').text(datos);
                    cargaBotones(datos, status, xhr);
                }
            });
        });

        $('.btn-unfollow').on('click', function () {
            $.ajax({
                url: "$urlUnfollow",
                method: 'POST',
                data: {'followed_id': $profile->user_id},
                success: function (datos, status, xhr) {
                    $('#followers').text(datos);
                    cargaBotones(datos, status, xhr);
                }
            });
        });

        $('#up, #down').on('click', function () {
            $.ajax({
                url: "$urlVote",
                method: 'POST',
                data: {'voted_id': $profile->user_id, 'positive': $(this).val()},
                success: function (datos, status, xhr) {
                    if (datos != undefined) {
                        $('#karma').text(datos);
                    }
                    cargaBotones(datos, status, xhr);
                }
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
    <div class="col-xs-12 col-sm-4">
        <div>
            <?= Html::img($profile->getAvatar(), [
                'class' => 'img-rounded img-responsive',
                'alt' => $profile->user->username,
            ]) ?>
            <h3><?= $this->title ?>
                <?php if ($profile->gender == 'Male'): ?>
                    <small><span class="fa fa-mars"></span></small>
                <?php elseif ($profile->gender == 'Female'): ?>
                    <small><span class="fa fa-venus"></span></small>
                <?php elseif ($profile->gender == 'Undefined'): ?>
                    <small><span class="fa fa-neuter"></span></small>
                <?php endif; ?>
            </h3>
            <div id="follows">
                <div class="user-statistics">
                    <p id="karma"><?= $karma ?></p>
                    <p>karma</p>
                </div>
                <div class="user-statistics">
                    <a href="#followers-list" id="followers"><?= $profile->totalFollowers ?></a>
                    <p><?= Yii::t('app', 'followers') ?></p>
                </div>
                <div class="user-statistics">
                    <a href="#followings-list"><?= $profile->totalFollowed ?></a>
                    <p><?= Yii::t('app', 'following') ?></p>
                </div>
                <div class="user-statistics">
                    <a href="#publications-list" id="link-publications-list"><?= $profile->totalPublications ?></a>
                    <p><?= Yii::t('app', 'publications') ?></p>
                </div>
            </div>
            <div id="column">
                <div class="toolbar" id="toolbar">
                <?php if (Yii::$app->user->isGuest || Yii::$app->user->id !== $profile->user_id) { ?>
                    <div class="btn-group" id="buttons">
                        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isFollower($profile->user_id)){ ?>
                            <button id="btn-follow" class="btn btn-danger btn-unfollow"><span class="glyphicon glyphicon-ok"></span> Siguiendo</button>
                        <?php } else { ?>
                            <button id="btn-follow" class="btn btn-default btn-follow"><span class="glyphicon glyphicon-user"></span> Seguir</button>
                        <?php } ?>
                    </div>
                    <div class="btn-group">
                        <button class="btn
                        <?= !Yii::$app->user->isGuest &&
                        Yii::$app->user->identity->hasVoted($profile->user_id) &&
                        Vote::findOne(['id_voter' => Yii::$app->user->id, 'id_voted' => $profile->user_id])->positive ? 'btn-danger' : 'btn-default'?>" id="up" value="1"><span class="glyphicon glyphicon-thumbs-up"></span></button>
                        <button class="btn
                        <?= !Yii::$app->user->isGuest &&
                        Yii::$app->user->identity->hasVoted($profile->user_id) &&
                        Vote::findOne(['id_voter' => Yii::$app->user->id, 'id_voted' => $profile->user_id])->positive == false ? 'btn-danger' : 'btn-default'?>" id="down" value="0"><span class="glyphicon glyphicon-thumbs-down"></span></button>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
        <br />
        <ul style="padding: 0; list-style: none outside none;">
            <?php if (!empty($profile->location)): ?>
                <li>
                    <span class="glyphicon glyphicon-map-marker text-muted"></span> <?= Html::encode($profile->location) ?>
                </li>
            <?php endif; ?>
            <?php if (!empty($profile->website)): ?>
                <li>
                    <span class="glyphicon glyphicon-globe text-muted"></span> <?= Html::a(Html::encode($profile->website), Html::encode($profile->website)) ?>
                </li>
            <?php endif; ?>
            <?php if (!empty($profile->public_email)): ?>
                <li>
                    <span class="glyphicon glyphicon-envelope text-muted"></span> <?= Html::a(Html::encode($profile->public_email), 'mailto:' . Html::encode($profile->public_email)) ?>
                </li>
            <?php endif; ?>
            <li>
                <span class="glyphicon glyphicon-time text-muted"></span> <?= Yii::t('user', 'Joined on {0, date}', $profile->user->created_at) ?>
            </li>
        </ul>
        <?php if (!empty($profile->bio)): ?>
            <p><?= Html::encode($profile->bio) ?></p>
        <?php endif; ?>
        <div>
            <h4><?= Yii::t('app', 'Collection') ?></h4>
            <?= ListView::widget([
                'dataProvider' => $collection,
                'itemOptions' => [
                    'tag' => 'article',
                    'class' => 'collectionp-view',
                ],
                'options' => [
                    'tag' => 'div',
                    'class' => 'collection-wrapper',
                    'id' => 'collection-wrapper',
                ],
                'layout' => "{items}\n{pager}",
                'itemView' => '../../collections/_view',
                'emptyText' => !Yii::$app->user->isGuest && Yii::$app->user->id == $profile->user_id ? (Yii::t('app', "You don't have games in you collection") . '<br /> ' .
                    Html::a(Yii::t('app','Add games to your collection'), ['/user/settings/collection'])) : Yii::t('app', "This doesn't have any games in his collection")
            ]) ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-8 user-lists">
        <div id="publications-list">
            <h2><?= Yii::t('app', 'Publications') ?></h2>
            <div>
                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $profile->user_id): ?>
                    <?= $this->render('../../publications/_form', [
                        'model' => $publication,
                        ]) ?>
                    <?php endif; ?>
                </div>
                <div>
                    <?= ListView::widget([
                        'dataProvider' => $publicationProvider,
                        'layout' => "{items}\n{pager}",
                        'options' => [
                            'tag' => 'div',
                            'class' => 'publications-wrapper',
                            'id' => 'publications-wrapper',
                        ],
                        'layout' => "{items}\n{pager}",
                        'itemView' => '../messages/_view.php',
                        'pager' => [
                            'class' => ScrollPager::className(),
                            'container' => '.publications-wrapper',
                            'triggerText' => Yii::t('app', 'Show old publications'),
                            'noneLeftText' => '',
                            //'overflowContainer' => '.list'
                        ],
                        'itemOptions' => [
                            'tag' => 'article',
                            'class' => 'publication-view',
                        ],
                        'itemView' => '../../publications/_view',
                        ]) ?>
                    </div>
        </div>
        <div id="followers-list">
            <h2><?= Yii::t('app', 'Followers') ?></h2>
            <?= ListView::widget([
                'dataProvider' => $followerProvider,
                'itemOptions' => [
                    'class' => 'user-view media',
                    'tag' => 'article',
                ],
                'options' => [
                    'tag' => 'div',
                    'class' => 'users-wrapper',
                    'id' => 'users-wrapper',
                ],
                'layout' => "{items}\n{pager}",
                'itemView' => '../_view',
            ]) ?>
        </div>
        <div id="followings-list">
            <h2><?= Yii::t('app', 'Following') ?></h2>
            <?= ListView::widget([
                'dataProvider' => $followingProvider,
                'itemOptions' => [
                    'class' => 'user-view media',
                    'tag' => 'article',
                ],
                'options' => [
                    'tag' => 'div',
                    'class' => 'users-wrapper',
                    'id' => 'users-wrapper',
                ],
                'layout' => "{items}\n{pager}",
                'itemView' => '../_view',
            ]) ?>
        </div>
    </div>
</div>
