<?php
namespace app\rbac;

use Yii;
use app\models\Member;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class AdminRule extends Rule
{
    public $name = 'isAdmin';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (isset(Yii::$app->request->queryParams['id_group']) &&
            (Yii::$app->request->pathInfo == 'members/confirm'
            || Yii::$app->request->pathInfo == 'members/reject'
            || Yii::$app->request->pathInfo == 'members/ban'
            || Yii::$app->request->pathInfo == 'members/promote'
            || Yii::$app->request->pathInfo == 'members/index'
            || Yii::$app->request->pathInfo == 'members/requests')) {
            $group = Yii::$app->request->queryParams['id_group'];
            $admin = Member::find()->where(['and', ['id_group' => $group], ['id_user' => Yii::$app->user->id], ['admin' => true]])->one();
            return  $admin !== null;
        }
        if (isset(Yii::$app->request->queryParams['id']) && Yii::$app->request->pathInfo == 'groups/view') {
            $group = Yii::$app->request->queryParams['id'];
            $admin = Member::find()->where(['and', ['id_group' => $group], ['id_user' => Yii::$app->user->id], ['admin' => true]])->one();
            return  $admin !== null;
        }
        return false;
    }
}
