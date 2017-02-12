<?php
namespace frontend\models;

use common\models\db\Comment;
use Yii;
use yii\base\Model;

class CommentForm extends Model
{
    /**
     * @var null|string action формы
     */
    public $action = null;
    /**
     * @var int|null идентификатор родительского комментария
     */
    public $parentId = null;
    /**
     * @var string заголовок комментария
     */
    public $name;
    /**
     * @var string контент комментария
     */
    public $content;

    public function __construct($action = null)
    {
        $this->action = $action;
        parent::__construct();
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parentId', 'postId'], 'integer'],
            [['title', 'content'], 'required'],
            [['title', 'content'], 'string', 'max' => 255]
        ];
    }
    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'content' => 'Content'
        ];
    }
    /**
     * Сохраняет комментарий.
     * @param Comment $comment модель комментария
     * @param array $data данные пришедшие из формы
     * @return bool
     */
    public function save(Comment $comment, array $data)
    {
        $isLoad = $comment->load([
            'parentId' => $data['parentId'],
            'title' => $data['name'],
            'content' => $data['content']
        ], '');
        return ($isLoad && $comment->save());
    }
}