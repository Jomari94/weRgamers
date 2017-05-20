<?php

use yii\bootstrap\Tabs;

$this->title = Yii::t('app', 'Search Results: {0}', [$q]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-search">
    <?= Tabs::widget([
    'items' => [
        [
            'label' => Yii::t('app', 'Users'),
            'content' => $this->render('/user/_searched', [
                'q' => $q,
                'userProvider' => $userProvider,
            ]),
            'active' => true
        ],
        [
            'label' => Yii::t('app', 'Games'),
            'content' => $this->render('/games/_searched', [
                'q' => $q,
                'gameProvider' => $gameProvider,
            ]),
        ],
        [
            'label' => Yii::t('app', 'Groups'),
            'content' => $this->render('/groups/_searched', [
                'q' => $q,
                'groupProvider' => $groupProvider,
            ]),
        ],
    ],
]); ?>
</div>
