<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%audit}}".
 *
 * @property int $id
 * @property string|null $operation
 * @property string|null $model
 * @property string|null $record_pk
 * @property string|null $attribute
 * @property string|null $old
 * @property string|null $new
 * @property int|null $timestamp
 * @property string|null $identity
 * @property int|null $identity_id
 * @property string|null $identity_ip
 */
class Audit extends ActiveRecord
{
    public static $alias = 'audit';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%audit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['old', 'new'], 'string'],
            [['timestamp', 'identity_id'], 'integer'],
            [['operation', 'model', 'record_pk', 'attribute', 'identity', 'identity_ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'operation' => Yii::t('common', 'Operation'),
            'model' => Yii::t('common', 'Model'),
            'record_pk' => Yii::t('common', 'Record Pk'),
            'attribute' => Yii::t('common', 'Attribute'),
            'old' => Yii::t('common', 'Old'),
            'new' => Yii::t('common', 'New'),
            'timestamp' => Yii::t('common', 'Timestamp'),
            'identity' => Yii::t('common', 'Identity'),
            'identity_id' => Yii::t('common', 'Identity ID'),
            'identity_ip' => Yii::t('common', 'Identity Ip'),
        ];
    }
}
