<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "source_message".
 *
 * @property int $id
 * @property string|null $category
 * @property string|null $message
 *
 * @property Message[] $messages
 */
class SourceMessage extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%source_message}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã'),
            'category' => Yii::t('common', 'Category'),
            'message' => Yii::t('common', 'Message'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['id' => 'id']);
    }
}
