<?php

namespace backend\models;


use common\models\ActiveQuery;
use common\util\AppHelper;
use Exception;
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Class Product
 * @package backend\models
 *
 * @property String $viewThumb
 */
class Product extends \common\models\Product
{
    /**
     * @return null
     */
    public function saveAsCreate()
    {
        if (!$this->validate()) {
            return NULL;
        }
        if ($this->save()) {
            $this->thumbnail = $this->upload();
            $this->gallery = $this->uploadGallery();
        }
        return $this->save();
    }

    /**
     * @return string|null
     */
    public function upload()
    {
        if ($this->isNewRecord || empty($this->image)) {
            return NULL;
        }
        $relativeDir = "public/product/{$this->id}";
        $absoluteDir = Yii::getAlias("@root/$relativeDir");
        $newFileName = uniqid() . '.' . $this->image->extension;
        $fileSize = $this->image->size;
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755);
        }
        $file = "{$absoluteDir}/$newFileName";
        $this->deleteOldImage();
        if ($this->image->saveAs($file)) {
            $result = Json::encode([
                'path' => "/$relativeDir/$newFileName",
                'size' => $fileSize,
                'caption' => $newFileName,
            ]);
            $this->image = NULL;
            return $result;
        }
        return NULL;
    }

    /**
     * @return bool
     */
    public function deleteOldImage()
    {
        if ($oldThumbnailJson = $this->oldAttributes['thumbnail']) {
            try {
                $oldThumbnail = Json::decode($oldThumbnailJson);
                return $this->deleteFile($oldThumbnail['path']);
            } catch (Exception $ex) {
                return FALSE;
            }
        }
        return FALSE;
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
        return FALSE;
    }

    /**
     * @return null
     */
    public function saveAsUpdate()
    {
        if (!$this->validate()) {
            return NULL;
        }
        if ($this->image) {
            $this->thumbnail = $this->upload();
        } else {
            if (!$this->thumbnail) {
                $this->deleteOldImage();
            }
            if (!empty($this->dirtyAttributes['thumbnail'])) {
                $this->thumbnail = $this->oldAttributes['thumbnail'];
            }
        }
        if ($this->images) {
            $this->gallery = $this->uploadGallery();
        }
        return $this->save();
    }

    /**
     * @param $option
     * @return array|mixed|null
     */
    public function previewThumbnail($option)
    {
        try {
            $thumbnail = Json::decode($this->thumbnail);
            if ($this->thumbnail) {
                $thumbnail['path'] = AppHelper::webHostRoot() . $thumbnail['path'];
                return $thumbnail[$option];
            }
        } catch (Exception $error) {
        }
        return NULL;
    }

    /**
     * @return bool
     * @throws ErrorException
     */
    public function remove()
    {
        $this->deleteDir("/public/product/{$this->id}");
        return $this->softDelete();
    }

    /**
     * @param $dirPath
     * @return false|void
     * @throws ErrorException
     */
    protected function deleteDir($dirPath)
    {
        if (!$dirPath) return NULL;
        $file = Yii::getAlias("@root$dirPath");
        if (file_exists($file)) {
            FileHelper::removeDirectory($file);
        }
    }

    /**
     * @return string|null
     */
    protected function uploadGallery()
    {
        if ($this->isNewRecord || empty($this->images)) {
            return NULL;
        }
        $relativeDir = "public/product/{$this->id}";
        $absoluteDir = Yii::getAlias("@root/$relativeDir");
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755);
        }
        $result = [];
        foreach ($this->images as $key => $image) {
            /** @var UploadedFile $image */
            $newFileName = uniqid() . '.' . $image->extension;
            $fileSize = $image->size;
            $path = "/$relativeDir/$newFileName";
            $file = "{$absoluteDir}/$newFileName";
            if ($image->saveAs($file)) {
                $result[] = [
                    'id' => uniqid(),
                    'path' => $path,
                    'size' => $fileSize,
                    'caption' => $newFileName,
                    'oder' => time(),
                ];
            }
        }
        try {
            $oldGallery = Json::decode($this->gallery);
            $result = ArrayHelper::merge($oldGallery, $result);
        } catch (Exception $exception) {

        }
        $this->images = NULL;
        return $result ? Json::encode($result) : NULL;
    }

    /**
     * @param $getConfig
     * @return array|null
     */
    public function previewGallery($getConfig = FALSE)
    {
        try {
            $gallery = Json::decode($this->gallery);
            $initialPreview = [];
            $initialPreviewConfig = [];
            foreach ($gallery as $item) {
                $initialPreview[] = AppHelper::webHostRoot() . $item['path'];
                $initialPreviewConfig[] = [
                    'key' => $item['id'],
                    'caption' => $item['caption'],
                    'size' => $item['size'],
                    'url' => Url::to(['product/delete-gallery', 'id' => $this->id]),
                ];
            }
            return $getConfig ? $initialPreviewConfig : $initialPreview;
        } catch (Exception $error) {
            return [];
        }
    }

    /**
     * @param $imageKey
     * @return bool
     */
    public function deleteGalleryOneImage($imageKey)
    {
        try {
            $gallery = Json::decode($this->gallery);
            foreach ($gallery as $key => $item) {
                if ($item['id'] == $imageKey) {
                    $this->deleteFile($item['path']);
                    unset($gallery[$key]);
                }
            }
            $galleryRaw = Json::encode($gallery);
            $this->gallery = $galleryRaw;
            return $this->save();
        } catch (Exception $ex) {
            return FALSE;
        }
    }

    /**
     * @return array|mixed|string|null
     */
    public function getViewThumb()
    {
        return $this->previewThumbnail('path');
    }

    /**
     * @param $filter
     * @return ActiveQuery
     */
    public static function findIndex($filter)
    {
        $query = Product::find()
            ->notDeleted()
            ->joinWith(['category category']);
        if (!empty($filter['name'])) {
            $query->andFilterWhere(['LIKE', Product::$alias . '.name', $filter['name']]);
        }
        if (!empty($filter['category'])) {
            $query->andFilterWhere(['=', Product::$alias . '.category_id', $filter['category']]);
        }
        if (!empty($filter['min'])) {
            $query->andFilterWhere(['>=', Product::$alias . '.inventory', $filter['min']]);
        }
        if (!empty($filter['max'])) {
            $query->andFilterWhere(['<=', Product::$alias . '.inventory', $filter['max']]);
        }
        return $query;
    }
}