<?php

namespace app\models;

use dektrium\user\helpers\Password;
use Yii;
use dektrium\user\models\SettingsForm as BaseSettingsForm;

/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsForm extends BaseSettingsForm
{

    /** @var string */
    public $repeat_password;

    /** @inheritdoc */
    public function rules()
    {
        return [
            'usernameRequired' => ['username', 'required'],
            'usernameTrim' => ['username', 'filter', 'filter' => 'trim'],
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernamePattern' => ['username', 'match', 'pattern' => '/^[-a-zA-Z0-9_\.@]+$/'],
            'emailRequired' => ['email', 'required'],
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailPattern' => ['email', 'email'],
            'emailUsernameUnique' => [['email', 'username'], 'unique', 'when' => function ($model, $attribute) {
                return $this->user->$attribute != $model->$attribute;
            }, 'targetClass' => $this->module->modelMap['User']],
            'newPasswordLength' => ['new_password', 'string', 'max' => 72, 'min' => 6],
            'currentPasswordRequired' => ['current_password', 'required'],
            'currentPasswordValidate' => ['current_password', function ($attr) {
                if (!Password::validate($this->$attr, $this->user->password_hash)) {
                    $this->addError($attr, Yii::t('user', 'Current password is not valid'));
                }
            }],
            'repeatPasswordValidate' => ['repeat_password', function ($attr) {
                if ($this->$attr !== $this->new_password) {
                    $this->addError($attr, Yii::t('user', 'Passwords do not match'));
                }
            }]
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email'            => Yii::t('user', 'Email'),
            'username'         => Yii::t('user', 'Username'),
            'new_password'     => Yii::t('user', 'New password'),
            'repeat_password'  => Yii::t('user', 'Repeat password'),
            'current_password' => Yii::t('user', 'Current password'),
        ];
    }
}
