<?php

namespace app\models;

use Yii;
use dektrium\user\models\Profile as BaseProfile;
use yii\helpers\FileHelper;
use phpDocumentor\Reflection\Types\String_;

class Profile extends BaseProfile
{
    /**
     * Se obtiene la ruta hacia el avatar del usuario, si no tiene se le
     * da un avatar por defecto
     * @return String_ ruta hacia el avatar
     */
    public function getAvatar()
    {
        $uploads = Yii::getAlias('@uploads');
        $files = FileHelper::findFiles($uploads);
        if (isset($files[0])) {
            foreach ($files as $index => $file) {
                $archivo = substr($file, strrpos($file, '/') + 1);
                $nombre = substr($archivo, 0, strlen($archivo) - 4);
                if (strlen($nombre) === 1 && intval($nombre) === $this->user_id) {
                    return "/$uploads/$archivo";
                }
            }
        }
        return "/$uploads/default.png";
    }

    /**
     * Se obtiene la ruta hacia el avatar del usuario, si no tiene se le
     * da un avatar por defecto
     * @return String_ ruta hacia el avatar
     */
    public function getAvatarMini()
    {
        $uploads = Yii::getAlias('@uploads');
        $files = FileHelper::findFiles($uploads);
        if (isset($files[0])) {
            foreach ($files as $index => $file) {
                $archivo = substr($file, strrpos($file, '/') + 1);
                $nombre = substr($archivo, 0, strlen($archivo) - 4);
                if ($nombre == "{$this->user_id}-mini") {
                    return "/$uploads/$archivo";
                }
            }
        }
        return "/$uploads/default-mini.png";
    }
}
