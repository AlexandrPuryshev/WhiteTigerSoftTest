<?php 

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return array(
            // Максимальный и минимальный размер указываем в байтах.
            array('imageFile', 'file', 'extensions'=>'jpg, gif, png', 'maxSize' => 1048576),
        );
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $images_path = realpath(Yii::$app->basePath . '/assets');
            Yii::warning($this->imageFile);
            Yii::warning(Yii::$app->basePath . '\\assets\\', 'imageData');
            $this->imageFile->saveAs($images_path . '/' . $this->imageFile . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}

?>