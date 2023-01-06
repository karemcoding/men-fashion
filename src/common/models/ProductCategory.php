<?php

namespace common\models;

use common\behaviors\nestedsets\NestedSetsBehavior;
use common\util\AppHelper;
use Exception;
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%product_category}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $thumbnail
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $depth
 * @property-read null|string|array|mixed $viewThumb
 * @property UploadedFile $image;
 * @property int $tree [int(11)]
 * @property int $lft [int(11)]
 * @property-read \yii\db\ActiveQuery $products
 * @property int $rgt [int(11)]
 *
 * @method initDefaults()
 * @method makeRoot()
 * @method appendTo() appendTo(ProductCategory $node)
 * @method insertBefore() insertBefore(ProductCategory $node)
 * @method insertAfter() insertAfter(ProductCategory $node)
 * @method ProductCategoryQuery parents() parents(int $depth = null)
 * @method ProductCategoryQuery children()
 * @method boolean isRoot()
 * @method boolean isLeaf()
 * @method boolean delete()
 * @method boolean deleteWithChildren()
 * @method boolean nestedSoftDelete()
 */
class ProductCategory extends ActiveRecord
{
    const EXTENSIONS = ['jpg', 'png', 'jpeg'];
    const ROOT_ID = 1;
    public $parent;
    public $image;
    public static $alias = 'productCategory';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_category}}';
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return array|string[]
     */
    public function behaviors()
    {
        $behavior = [
            'tree' => [
                'class' => NestedSetsBehavior::class,
                'treeAttribute' => 'tree',
            ]
        ];
        return ArrayHelper::merge(parent::behaviors(), $behavior);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'updated_by',
                'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['thumbnail'], 'string', 'max' => 1000],
            [['parent'], 'integer'],
            [['parent'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => ProductCategory::class,
                'targetAttribute' => ['parent' => 'id']],
            [['image'], 'file',
                'skipOnEmpty' => true,
                'skipOnError' => true,
                'extensions' => self::EXTENSIONS,
                'maxSize' => 1048576]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã'),
            'name' => Yii::t('common', 'Tên danh mục'),
            'description' => Yii::t('common', 'Mô tả'),
            'thumbnail' => Yii::t('common', 'Ảnh đại diện'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'parent' => Yii::t('common', 'Danh mục cha'),
            'image' => Yii::t('common', 'Hình ảnh')
        ];
    }

    public static function list2Select($selected, $prompt = 'No parent')
    {
        $noParent = [
            'id' => '',
            'text' => Yii::t('common', $prompt),
            'html' => Yii::t('common', $prompt),
        ];
        return ArrayHelper::merge([$noParent], self::build2Select($selected));
    }

    /**
     * @param $selected
     * @param array $categories
     * @param array $result
     * @param string $prefix
     * @return array|mixed
     */
    public static function build2Select($selected, $categories = [], $result = [], $prefix = '')
    {
        /** @var ProductCategory[] $categories */
        if (empty($categories)) {
            $categories = self::listAll();
        }
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category['id'],
                'text' => $category['name'],
                'html' => $prefix . $category['name'],
                'selected' => $category['id'] == $selected
            ];
            if (!empty($category['children'])) {
                $result = self::build2Select($selected, $category['children'], $result, $prefix . "&emsp;");
            }
        }
        return $result;
    }

    public static function listAll()
    {
        $result = [];
        $categories = ProductCategory::find()->andWhere(['id' => self::ROOT_ID])->all();
        foreach ($categories as $category) {
            /** @var ProductCategory $category */
            $result = self::createTree($category->children()->asArray()->all());
        }
        return $result;
    }

    /**
     * @return ProductCategoryQuery
     */
    public static function find()
    {
        return new ProductCategoryQuery(get_called_class());
    }

    /**
     * @param $menu
     * @param int $left
     * @param null $right
     *
     * @return array
     */
    public static function createTree($menu, $left = 1, $right = NULL)
    {
        $tree = [];
        foreach ($menu as $range) {
            if ($range['lft'] == $left + 1 && (is_null($right) || $range['rgt'] < $right)) {
                $range['children'] = self::createTree($menu, $range['lft'], $range['rgt']);
                $tree_leaf = [
                    'id' => $range['id'],
                    'name' => $range['name'],
                    'description' => $range['description'],
                    'thumbnail' => $range['thumbnail'],
                    'status' => $range['status'],
                ];

                if (!empty($range['children'])) {
                    $tree_leaf['children'] = $range['children'];
                }

                $tree[] = $tree_leaf;

                $left = $range['rgt'];
            }
        }

        return $tree;
    }

    /**
     * @return null
     */
    public function saveAsCreate()
    {
        if (!$this->validate()) {
            return null;
        }

        if (empty($this->parent)) {
            $this->parent = self::ROOT_ID;
        }
        if ($parent = ProductCategory::findOne(['id' => $this->parent])) {
            if ($this->appendTo($parent)) {
                $this->thumbnail = $this->upload();
                return $this->save();
            }
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function upload()
    {
        if ($this->isNewRecord || empty($this->image)) {
            return null;
        }
        $relativeDir = "public/product_category/{$this->id}";
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
            $this->image = null;
            return $result;
        }
        return null;
    }

    /**
     * @return bool
     */
    protected function deleteOldImage()
    {
        if ($oldThumbnailJson = $this->oldAttributes['thumbnail']) {
            try {
                $oldThumbnail = Json::decode($oldThumbnailJson);
                return $this->deleteFile($oldThumbnail['path']);
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
     * @return null
     */
    public function saveAsUpdate()
    {
        if (!$this->validate()) {
            return null;
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
            if ($thumbnail) {
                $thumbnail['path'] = AppHelper::webHostRoot() . $thumbnail['path'];
                return $thumbnail[$option];
            }
        } catch (Exception $error) {
        }
        return null;
    }

    /**
     * @return bool
     * @throws ErrorException
     */
    public function remove()
    {
        $this->deleteDir("public/product_category/{$this->id}");
        return $this->nestedSoftDelete();
    }

    /**
     * @param $dirPath
     * @return false|void
     * @throws ErrorException
     */
    protected function deleteDir($dirPath)
    {
        if (!$dirPath) return null;
        $file = Yii::getAlias("@root/$dirPath");
        if (file_exists($file)) {
            FileHelper::removeDirectory($file);
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
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }
}
