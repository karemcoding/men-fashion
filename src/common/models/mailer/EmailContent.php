<?php

namespace common\models\mailer;

use common\models\ActiveRecord;
use common\util\Status;
use Symfony\Component\Yaml\Yaml;
use Yii;

/**
 * This is the model class for table "{{%email_content}}".
 *
 * @property int $id
 * @property string|null $template
 * @property string|null $subject
 * @property string|null $content
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $created_at
 * @property int|null $updated_by
 * @property int|null $updated_at
 */
class EmailContent extends ActiveRecord
{
    /**
     * @var DynamicEmailTemplate
     */
    public $templateObj;

    public $templateTemp;

    public function init()
    {
        parent::init();
        $this->setTemplateObj();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%email_content}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['template', 'subject', 'content', 'templateTemp'], 'required'],
            [['content'], 'string'],
            [[
                'status',
                'created_by', 'created_at',
                'updated_by', 'updated_at',
            ], 'integer'],
            [['template'], 'string', 'max' => 255],
            [['subject'], 'string', 'max' => 1000],
            [['template'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'template' => Yii::t('common', 'Template'),
            'subject' => Yii::t('common', 'Subject'),
            'content' => Yii::t('common', 'Content'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'templateTemp' => Yii::t('common', 'Template'),
        ];
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        return [
            'content' => Yii::t('common', 'Available variables: {0}',
                [implode(', ', $this->templateObj->templateParams)]),
        ];
    }

    /**
     * @return array|mixed
     */
    public function findTemplates(): array
    {
        $path = Yii::getAlias("@common/models/mailer/email_template.yml");
        if (file_exists($path)) {
            return Yaml::parse(file_get_contents($path));
        }
        return [];
    }

    protected function setTemplateObj()
    {
        if ($this->template) {
            $templates = $this->findTemplates();
            if (!empty($templates[$this->template])) {
                $this->templateObj = new DynamicEmailTemplate($templates[$this->template]);
                $this->templateObj->key = $this->template;
            }
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->setTemplateObj();
    }

    /**
     * @param $key
     * @return EmailContent|null
     */
    public static function findKey($key): ?EmailContent
    {
        return self::findOne([
            'template' => $key,
            'status' => Status::STATUS_ACTIVE,
        ]);
    }
}
