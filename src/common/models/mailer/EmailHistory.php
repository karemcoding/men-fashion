<?php

namespace common\models\mailer;

use common\models\ActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%email_history}}".
 *
 * @property int $id
 * @property string|null $receiver
 * @property string|null $subject
 * @property string|null $content
 * @property int|null $sent_at
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $created_at
 * @property int|null $updated_by
 * @property int|null $updated_at
 */
class EmailHistory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%email_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receiver', 'content'], 'string'],
            [['sent_at', 'status',
                'created_by', 'created_at',
                'updated_by', 'updated_at'], 'integer'],
            [['subject'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'receiver' => Yii::t('common', 'Receiver'),
            'subject' => Yii::t('common', 'Subject'),
            'content' => Yii::t('common', 'Content'),
            'sent_at' => Yii::t('common', 'Sent At'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
        ];
    }
}
