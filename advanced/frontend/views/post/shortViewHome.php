<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use \yii\web\YiiAsset;

/* @var $model common\models\Post */
/* @var TagPost $postTag */
?>


<div class = "content_posts">

	<h1>Title: <?= $model->title ?></h1>

	<div class="meta">
	<br>
	<?php
		if(isset($model->image))
		{ 
			echo Html::img(Url::to(Yii::getAlias('@imageUrlPath')) . '/' . $model->image, ['style' => 'width: 100%;']);
		}
	?>
	<br>
	    <p> Author: <?= $model->author->username ?> </p>
	    <p> Data of publish: <?= $model->createdAt ?> </p>
	    <?php
	    	if (isset($model->category)) { 
	    		echo "<p> Category: " . Html::a($model->category->name, ['category/view', 'id' => $model->category->id])  . "</p>";
	    	} 
	    ?>
	    <p> Status of this post: <?= $model->publishStatus ?>
	</div>

	<div class="content">
	    Description: <?= $model->anons ?>
	</div>

	<br>

	<?= Html::a('Read more', ['post/view', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
</div>


<hr class = "smallHr">