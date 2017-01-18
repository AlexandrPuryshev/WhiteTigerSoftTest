<?php

namespace frontend\controllers;

use frontend\models\Category;
use Yii;
use frontend\models\Post;
use common\controllers\PostControllerBase;


class PostController extends PostControllerBase
{
    public function actionHome()
    {
         $category = new Category();
         $postQuery = Post::findPublishedPosts();
         $dataProvider = parent::newPostDataProvider($postQuery);
         return parent::renderIndex($dataProvider, $category, 'indexHome');
    }
}
