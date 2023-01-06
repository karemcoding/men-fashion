<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%express_company}}".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $english_name
 * @property string|null $tel
 * @property string|null $address
 * @property string|null $website
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ExpressCompany extends ActiveRecord
{
    public static $alias = 'expressCompany';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%express_company}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['website'], 'url'],
            [['code', 'name','tel'], 'string', 'max' => 255],
            [['code', 'name','tel', 'address'], 'required'],
            [['address'], 'string', 'max' => 1000],
            [['code'], 'validateDuplicate']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã đơn vị vận chuyển'),
            'code' => Yii::t('common', 'Mã'),
            'name' => Yii::t('common', 'Tên'),
            'english_name' => Yii::t('common', 'Tên quốc tế'),
            'tel' => Yii::t('common', 'Tel'),
            'address' => Yii::t('common', 'Address'),
            'website' => Yii::t('common', 'Website'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
        ];
    }

    /**
     * @return array
     */
    public static function selectExpress()
    {
        $records = self::find()->active()->all();
        return ArrayHelper::map($records, 'id', 'name');
    }
}
