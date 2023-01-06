<?php

namespace api\models;

use common\models\ProductCategory;
use common\util\Status;
use yii\db\ActiveRecord;

/**
 * Class Category
 * @package api\models
 */
class Category extends ProductCategory
{
    /**
     * @return Category[]|array|ActiveRecord[]
     */
    static public function getAll()
    {
        return Category::find()
            ->select(['id', 'name', 'description', 'thumbnail', 'depth', 'lft'])
            ->andWhere(['status' => Status::STATUS_ACTIVE])
            ->andWhere(['<>', 'id', Category::ROOT_ID])
            ->asArray()
            ->orderBy(['lft' => SORT_ASC])
            ->all();
    }

    /**
     * @return Category[]|array|ActiveRecord[]
     */
    static public function getOne($id)
    {
        return Category::find()
            ->select(['id', 'name', 'description', 'thumbnail', 'depth', 'lft'])
            ->where(['id' => $id])
            ->one();
    }
}
