<?php

namespace common\models\settings;

use common\util\AppHelper;
use Exception;
use ReflectionException;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

/**
 * Class General
 * @package common\models\settings
 */
class General extends Setting
{
    const EXTENSIONS = ['jpg', 'png', 'jpeg'];
    public $name;
    public $address;
    public $tel;
    public $image;
    public $logo;
    public $lng;
    public $lat;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'logo', 'address', 'tel'], 'string'],
            [['lat', 'lng'], 'double'],
            [['name'], 'required'],
            [['image'], 'file',
                'skipOnEmpty' => true,
                'skipOnError' => true,
                'extensions' => self::EXTENSIONS,
                'maxSize' => 1048576
            ],
        ];
    }

    /**
     * @return bool|int
     * @throws ReflectionException
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function store()
    {
        if ($this->image) {
            $this->logo = $this->upload();
        }
        return $this->save();
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('common', 'Tên'),
            'tel' => Yii::t('common', 'Số điện thoại'),
            'address' => Yii::t('common', 'Địa chỉ'),
            'image' => Yii::t('common', 'Logo'),
        ];
    }

    /**
     * @return string|null
     */
    public function upload()
    {
        if (empty($this->image)) {
            return null;
        }
        $relativeDir = "public/app/logo";
        $absoluteDir = Yii::getAlias("@root/$relativeDir");
        $newFileName = uniqid() . '.' . $this->image->extension;
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755);
        }
        $file = "{$absoluteDir}/$newFileName";
        $this->deleteOldImage();
        if ($this->image->saveAs($file)) {
            $this->image = null;
            return "/$relativeDir/$newFileName";
        }
        return null;
    }

    /**
     * @return bool
     */
    public function deleteOldImage()
    {
        if ($oldAvatar = $this->logo) {
            try {
                return $this->deleteFile($oldAvatar);
            } catch (Exception $ex) {
                return false;
            }
        }
        return false;
    }

    /**
     * @param $filePath
     * @return bool
     */
    protected function deleteFile($filePath)
    {
        $file = Yii::getAlias("@root$filePath");
        if (file_exists($file)) {
            return FileHelper::unlink($file);
        }
        return false;
    }

    /**
     * @return string
     */
    public function previewLogo()
    {
        if (!$this->logo) return null;
        return AppHelper::webHostRoot() . $this->logo;
    }
}
