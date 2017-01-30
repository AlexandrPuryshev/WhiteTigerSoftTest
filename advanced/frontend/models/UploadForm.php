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
    
    public function upload()
    {
        if(isset($this->imageFile->baseName)){
            if ($this->validate()) {
                $this->name = "image-prefix-" . time() . '-' . $this->imageFile->baseName;
                $this->imageFile->saveAs(Yii::getAlias('@imageUrlPath'). '\\' . $this->name . '.' . $this->imageFile->extension);
                return true;
            }
        }
        return false;
    }
}

?>