<?php

namespace api\models;

use common\models\ProductDiscount;
use common\util\Status;
use yii\db\ActiveRecord;

/**
 * Class Product
 * @package api\models
 */
class Product extends \common\models\Product
{
    /**
     * @return Product[]|array|ActiveRecord[]
     */
    public static function findHot($limit)
    {
        return Product::find()
            ->selectDefault()
            ->withCategory()
            ->andWhere([
                'this.status' => Status::STATUS_ACTIVE,
                'this.hot' => Status::STATUS_ACTIVE,
            ])
            ->limit($limit)
            ->orderBy(['this.id' => SORT_DESC])
            ->asArray()
            ->all();
    }

    /**
     * @param $customerId
     * @return Product[]|array|ActiveRecord[]
     */
    public static function findWithCustomerCart($customerId)
    {
        return static::find()->selectDefault()
            ->withCategory()
            ->addSelect([
                'total' => '(this.price*carts.quantity)',
                'carts.quantity',
            ])
            ->joinWith(['carts carts'], FALSE)
            ->andWhere(['carts.customer_id' => $customerId])
            ->asArray()
            ->all();
    }

    /**
     * @return Product[]|array|ActiveRecord[]
     */
    public static function findMaybeLike()
    {
        return static::find()->selectDefault()
            ->addOrderBy(['score' => SORT_DESC])
            ->withCategory()
            ->asArray()
            ->all();
    }

    /**
     * @param $limit
     * @param $offset
     * @param $category
     * @param $ignore
     * @return Product[]|array|ActiveRecord[]
     */
    static public function getList($limit, $offset, $category, $ignore, $sort, $filter, $search)
    {
        if (!$category) {
            $category = 1;
        }

        $cate = Category::find()->where(['id' => $category])->one();
        $list = [];

        foreach ($cate->children()->all() as $child) {
            $list[] = $child->id;
        }
        $list[] = $cate->id;

        $query = static::find()->selectDefault()
            ->andWhere(['this.parent_id' => null])
            ->withCategory()
            ->andFilterWhere(['in', 'category.id', $list])
            ->andFilterWhere(['<>', 'this.id', $ignore])
            ->limit($limit)
            ->offset($offset)
            ->asArray();

        if ($sort == 1) {
            $query = $query->orderBy(['this.price' => SORT_ASC]);
        } else if ($sort == 2) {
            $query = $query->orderBy(['this.price' => SORT_DESC]);
        } else if ($sort == 3) {
            $query = $query->orderBy(['score' => SORT_DESC]);
        } else if ($sort == 4) {
            $query = $query->orderBy(['this.created_at' => SORT_DESC]);
        }
        if ($filter == 1) {
            $query = $query->andFilterWhere(['this.hot' => Status::STATUS_ACTIVE]);
        } else if ($filter == 2) {
             $query = $query->andFilterWhere(['>', 'discount.to',time()]);
        }
        if ($search != 'null') {
            $query = $query->andFilterWhere(['LIKE', 'this.name', $search]);
        }
        return $query->all();
    }

    /**
     * @param $ids
     * @return Product[]|array|ActiveRecord[]
     */
    static public function findMultiple($ids)
    {
        return static::find()->selectDefault()
            ->withCategory()
            ->andWhere(['this.id' => $ids])
            ->orderBy(['this.id' => SORT_DESC])
            ->asArray()
            ->all();
    }
}
