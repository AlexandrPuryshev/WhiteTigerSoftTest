<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use \yii\web\YiiAsset;
?>

<div class="comments">
	<?php /** @var Comment $comment */ ?>
	<?php foreach($model->comments as $comment) : ?>
	    <div class="comment">
	        <h3><?= htmlspecialchars($comment->title) ?></h3>
	        <div class="meta">Author: <strong><?=isset($comment->author) ? $comment->author->username : null?></strong></div>
	        <div>Description: <?= htmlspecialchars($comment->content) ?></div>
	    </div>
	    <hr class="smallHr">
	<?php endforeach; ?>
</div>