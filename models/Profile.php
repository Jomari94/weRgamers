<?php

namespace app\models;

use Yii;
use dektrium\user\models\Profile as BaseProfile;
use yii\helpers\FileHelper;
use phpDocumentor\Reflection\Types\String_;

class Profile extends BaseProfile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'bioString'            => ['bio', 'string'],
            'timeZoneValidation'   => ['timezone', 'validateTimeZone'],
            'publicEmailPattern'   => ['public_email', 'email'],
            'gravatarEmailPattern' => ['gravatar_email', 'email'],
            'websiteUrl'           => ['website', 'url'],
            'nameLength'           => ['name', 'string', 'max' => 255],
            'publicEmailLength'    => ['public_email', 'string', 'max' => 255],
            'gravatarEmailLength'  => ['gravatar_email', 'string', 'max' => 255],
            'locationLength'       => ['location', 'string', 'max' => 255],
            'websiteLength'        => ['website', 'string', 'max' => 255],
            'gender'               => ['gender', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'           => \Yii::t('user', 'Your name'),
            'public_email'   => \Yii::t('user', 'Email (public)'),
            'gravatar_email' => \Yii::t('user', 'Gravatar email'),
            'location'       => \Yii::t('user', 'Location'),
            'website'        => \Yii::t('user', 'Website'),
            'bio'            => \Yii::t('user', 'Bio'),
            'timezone'       => \Yii::t('user', 'Time zone'),
            'gender'         => \Yii::t('user', 'Gender'),
        ];
    }

    /**
     * Se obtiene la ruta hacia el avatar del usuario, si no tiene se le
     * da un avatar por defecto
     * @return String_ ruta hacia el avatar
     */
    public function getAvatar()
    {
        $avatars = Yii::getAlias('@avatars');
        $files = FileHelper::findFiles($avatars);
        if (isset($files[0])) {
            foreach ($files as $index => $file) {
                $archivo = substr($file, strrpos($file, '/') + 1);
                $nombre = substr($archivo, 0, strlen($archivo) - 4);
                if (strlen($nombre) === 1 && intval($nombre) === $this->user_id) {
                    return "/$avatars/$archivo";
                }
            }
        }
        return "/$avatars/default.png";
    }
}
