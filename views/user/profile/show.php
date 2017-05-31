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
cargaBotones();

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
            <h3><?= $this->title ?></h3>
            <div id="follows" class="row">
                <span class="col-xs-3">
                    <h3 id="karma"><?= $karma ?></h3> Karma
                </span>
                <span class="col-xs-3">
                    <h3 id="followers"><?= $profile->totalFollowers ?></h3> <?= Yii::t('app', 'followers') ?>
                </span>
                <span class="col-xs-3">
                    <h3><?= $profile->totalFollowed ?></h3> <?= Yii::t('app', 'following') ?>
                </span>
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
            ]) ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-8">
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
</div>
