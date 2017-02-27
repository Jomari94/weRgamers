<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\Menu;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/**
 * @var dektrium\user\models\User $user
 */
$user = Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= Html::img($user->profile->getAvatarMini(), [
                'class' => 'img-rounded',
                'alt' => $user->username,
            ]) ?>
            <?= $user->username ?>
        </h3>
    </div>
    <div class="panel-body">
        <?= Menu::widget([
            'options' => [
                'class' => 'nav nav-pills nav-stacked',
            ],
            'items' => [
                ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile']],
                ['label' => Yii::t('user', 'Account'), 'url' => ['/user/settings/account']],
                [
                    'label' => Yii::t('user', 'Networks'),
                    'url' => ['/user/settings/networks'],
                    'visible' => $networksVisible
                ],
            ],
        ]) ?>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            Avatar
        </h3>
    </div>
    <div class="panel-body">
        <?= Html::img($user->profile->getAvatar(), [
            'class' => 'img-thumbnail',
            'alt' => $user->username,
            ]) ?>
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data']
            ])
            ?>
            <?= $form->field($upload, 'imageFile')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => false,
                    'showUpload' => true,
                ],
            ])->label(false) ?>
            <?php ActiveForm::end() ?>
    </div>
</div>
