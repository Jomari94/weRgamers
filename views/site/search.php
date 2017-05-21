<?php

use yii\bootstrap\Tabs;

$this->title = Yii::t('app', 'Search Results: {0}', [$q]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-search">
    <h3><?= Yii::t('app', 'Searched: {0}', [$q]) ?></h3>
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
            'label' => Yii::t('app', 'Users by game'),
            'content' => $this->render('/user/_searched', [
                'q' => $q,
                'userProvider' => $userByGameProvider,
            ]),
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
        [
            'label' => Yii::t('app', 'Groups by game'),
            'content' => $this->render('/groups/_searched', [
                'q' => $q,
                'groupProvider' => $groupByGameProvider,
            ]),
        ],
        [
            'label' => Yii::t('app', 'Messages'),
            'content' => '<br />Próximamente',
        ],
    ],
]); ?>
</div>
