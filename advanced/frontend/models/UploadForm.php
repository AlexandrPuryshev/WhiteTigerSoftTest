<?php 

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public $name;

    public function rules()
    {
        return array(
            // Максимальный и минимальный размер указываем в байтах.
            array('imageFile', 'file', 'extensions'=>'jpg', 'maxSize' => 1048576),
        );
    }
    
    public function upload($object)
    {
        Yii::warning($object);
        Yii::warning(isset($this->imageFile->baseName));
        Yii::warning($this->validate());

        if(isset($this->imageFile->baseName)){
            if ($this->validate()) {
                $this->name = "image-prefix-" . time() . '-' . $this->imageFile->baseName;
                if($object == "post"){
                    $this->imageFile->saveAs(Yii::getAlias('@imageUrlPathPost'). '\\' . $this->name . '.' . $this->imageFile->extension);
                }
                else if($object == "message"){
                    $this->imageFile->saveAs(Yii::getAlias('@imageUrlPathChat'). '\\' . $this->name . '.' . $this->imageFile->extension);
                }
                return true;
            }
        }
        return false;
    }
}

?>