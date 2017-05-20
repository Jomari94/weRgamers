<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use app\models\User;
use app\models\Group;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Cookie;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Notification;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return Yii::$app->user->isGuest ?
            $this->run('/user/security/login') :
            $this->run('/user/profile/show', [
                'id' => Yii::$app->user->id
            ]);
    }

    /**
     * Cambia el lenguaje en el que se muestra la página
     * @return mixed
     */
    public function actionLanguage()
    {
        $language = Yii::$app->request->post('language');
        Yii::$app->language = $language;

        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user;
            $user->identity->profile->language = $language;
            $user->identity->profile->save();
        } else {
            $languageCookie = new Cookie([
                'name' => 'language',
                'value' => $language,
                'expire' => time() + 60 * 60 * 24 * 30, // 30 dias
            ]);
            Yii::$app->response->cookies->add($languageCookie);
        }
        return $this->goBack();
    }

    /**
     * Busca usuarios para darselo a bloodhound
     * @param  string $q Término a buscar
     * @return string    Resultados de la búsqueda
     */
    public function actionBloodhoundUsers($q = null)
    {
        if ($q !== null && $q !== '') {
            $users = User::find()->select('id, username')->where(['ilike', 'username', $q])->all();
            $response = [];
            foreach ($users as $user) {
                $response[] = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'avatar' => $user->profile->avatar,
                    'followers' => $user->profile->totalFollowers,
                    'karma' => $user->karma,
                ];
            }
            return json_encode($response);
        }
    }

    /**
     * Busca juegos para darselo a bloodhound
     * @param  string $q Término a buscar
     * @return string    Resultados de la búsqueda
     */
    public function actionBloodhoundGames($q = null)
    {
        if ($q !== null && $q !== '') {
            $games = Game::find()->select('id, name')->where(['ilike', 'name', $q])->all();
            $response = [];
            foreach ($games as $game) {
                $response[] = [
                    'id' => $game->id,
                    'name' => $game->name,
                    'cover' => $game->cover,
                    'score' => $game->score,
                    'reviews' => $game->totalReviews,
                ];
            }
            return json_encode($response);
        }
    }

    /**
     * Lista los resultados de una búsqueda
     * @param  string $q Término a buscar
     * @return mixed
     */
    public function actionSearch($q = null)
    {
        if ($q !== null && $q !== '') {
            $userProvider = new ActiveDataProvider([
                'query' => User::find()->where(['ilike', 'username', $q]),
            ]);
            $gameProvider = new ActiveDataProvider([
                'query' => Game::find()->where(['ilike', 'name', $q]),
            ]);
            $groupProvider = new ActiveDataProvider([
                'query' => Group::find()->where(['ilike', 'name', $q]),
            ]);
            return $this->render('search', [
                'q' => $q,
                'userProvider' => $userProvider,
                'gameProvider' => $gameProvider,
                'groupProvider' => $groupProvider,
            ]);
        }
        return $this->refresh();
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Borra las notificaciones ya vistas
     * @return void
     */
    public function actionNotificated()
    {
        Notification::deleteAll(['id_receiver' => Yii::$app->user->id]);
    }
}
