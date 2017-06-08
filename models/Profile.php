<?php

namespace app\models;

use Yii;
use dektrium\user\models\Profile as BaseProfile;
use yii\helpers\FileHelper;

/**
 * Perfil del usuario
 */
class Profile extends BaseProfile
{
    /**
     * Reglas del modelo.
     * @return array
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
            'language'             => ['language', 'string', 'max' => 5],
        ];
    }

    /**
     * Labels de las propiedades del modelo.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name'           => \Yii::t('app', 'Your name'),
            'public_email'   => \Yii::t('user', 'Email (public)'),
            'gravatar_email' => \Yii::t('user', 'Gravatar email'),
            'location'       => \Yii::t('user', 'Location'),
            'website'        => \Yii::t('user', 'Website'),
            'bio'            => \Yii::t('user', 'Bio'),
            'timezone'       => \Yii::t('app', 'Time zone'),
            'gender'         => \Yii::t('app', 'Gender'),
            'language'       => \Yii::t('user', 'Language'),
        ];
    }

    /**
     * Se obtiene la ruta hacia el avatar del usuario, si no tiene se le
     * da un avatar por defecto.
     * @return string ruta hacia el avatar
     */
    public function getAvatar()
    {
        $avatars = Yii::getAlias('@avatars');
        $files = FileHelper::findFiles($avatars);
        if (isset($files[0])) {
            foreach ($files as $file) {
                $archivo = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
                $nombre = substr($archivo, 0, strrpos($archivo, '.'));
                if (intval($nombre) === $this->user_id) {
                    return "/$avatars/$archivo";
                }
            }
        }
        return "/$avatars/default.png";
    }

    /**
     * Calcula el numero total de seguidores que tiene el usuario.
     * @return int      Número de seguidores
     */
    public function getTotalFollowers()
    {
        return Follower::find()->where(['id_followed' => $this->user_id])->count();
    }

    /**
     * Calcula el numero total de seguidos que tiene el usuario.
     * @return int      Número de seguidos
     */
    public function getTotalFollowed()
    {
        return Follower::find()->where(['id_follower' => $this->user_id])->count();
    }

    /**
     * Calcula el numero total de publicaciones que tiene el usuario.
     * @return int      Número de publicaciones
     */
    public function getTotalPublications()
    {
        return Publication::find()->where(['id_user' => $this->user_id])->count();
    }

    /**
     * Calcula el numero total de juegos que tiene el usuario.
     * @return int      Número de juegos
     */
    public function getTotalCollection()
    {
        return Collection::find()->where(['id_user' => $this->user_id])->count();
    }
}
