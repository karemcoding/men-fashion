<?php

namespace common\models;

use Exception;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $language
 * @property string|null $translation
 *
 * @property SourceMessage $source
 */
class Message extends ActiveRecord
{
    const SCENARIO_ADD_SOURCE = 'add_source';

    public $baseContent;

    public function behaviors()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'language', 'translation'], 'required'],
            [['baseContent'], 'required', 'on' => [self::SCENARIO_ADD_SOURCE]],
            [['id'], 'integer'],
            [['translation', 'baseContent'], 'string'],
            [['language'], 'in', 'range' => self::supportLanguages()],
            [['id', 'language'], 'unique',
                'targetAttribute' => ['id', 'language']],
            [['id'], 'exist', 'skipOnError' => true,
                'targetClass' => SourceMessage::class,
                'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'baseContent' => Yii::t('common', 'Content'),
            'language' => Yii::t('common', 'Language'),
            'translation' => Yii::t('common', 'Translation'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(SourceMessage::class, ['id' => 'id']);
    }

    /**
     * @return array
     */
    public static function supportLanguages()
    {
        return array_keys(self::language2Select());
    }

    /**
     * @return array
     */
    public static function language2Select()
    {
        return [
            'vi-VN' => Yii::t('common', 'Vietnamese'),
            'en-US' => Yii::t('common', 'English'),
            'zh-TW' => Yii::t('common', 'Chinese'),
        ];
    }

    /**
     * @return bool
     */
    public function add()
    {
        $source = new SourceMessage(['category' => 'common', 'message' => $this->baseContent]);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($source->save()) {
                $this->id = $source->id;
                $this->save();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return true;
    }
}
