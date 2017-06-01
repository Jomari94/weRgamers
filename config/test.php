<?php
$params = require(__DIR__ . '/params.php');
$dbParams = require(__DIR__ . '/test_db.php');

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'aliases' => [
        '@avatars' => '@app/web/avatars',
        '@covers' => '@app/web/covers',
        '@attachments' => '@app/web/attachments',
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'controllerMap' => [
                'settings' => 'app\controllers\user\SettingsController',
                'profile' => 'app\controllers\user\ProfileController',
                'registration' => [
                    'class' => \dektrium\user\controllers\RegistrationController::className(),
                    'on ' . \dektrium\user\controllers\RegistrationController::EVENT_AFTER_REGISTER => function ($e) {
                        Yii::$app->response->redirect(array('/user/security/login'))->send();
                        Yii::$app->end();
                    }
                ],
            ],
            'modelMap' => [
                'Profile' => 'app\models\Profile',
                'SettingsForm' => 'app\models\SettingsForm',
                'User' => 'app\models\User',
            ],
            'enableUnconfirmedLogin' => true,
            'enableAccountDelete' => true,
            'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['Jomari'],
            'mailer' => [
                'sender' => ['wearegamers@firemail.cc' => "We 'r' Gamers"]
            ],
        ],
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ],
    'components' => [
        'db' => $dbParams,
        'mailer' => [
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
        ],
        // 'user' => [
        //     'identityClass' => 'app\models\User',
        // ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    'params' => $params,
];
