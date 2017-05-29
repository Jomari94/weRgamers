<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\FontAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
FontAsset::register($this);
$js = <<<EOT
EOT;
$this->registerJs($js);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="body-message">
<?php $this->beginBody() ?>

<div class="wrap">
        <header>
            <nav role="navigation">
                <div>
                    <a href="/conversations/index"><?= Yii::t('app', 'Mensajes') ?></a>
                </div>
            </nav>
        </header>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
