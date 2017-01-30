<?php
namespace frontend\controllers;

use common\models\db\CategoryModel;
use Yii;
use common\models\db\PostModel;
use frontend\controllers\PostController;

class HomeController extends PostController
{
    public function actionHome()
    {
         $category = new CategoryModel();
         $postQuery = PostModel::findPublishedPosts();
         $dataProvider = parent::newPostDataProvider($postQuery);
         return parent::renderIndex($dataProvider, $category, '..\post\indexHome');
    }
}