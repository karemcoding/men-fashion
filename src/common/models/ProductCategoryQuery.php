<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 11:27 PM 4/18/2021
 * @projectName baseProject by ANDY
 */

namespace common\models;

use common\behaviors\nestedsets\NestedSetsQueryBehavior;

/**
 * Class ProductCategoryQuery
 * @package common\models
 */
class ProductCategoryQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::class,
        ];
    }

    /**
     * @return ProductCategoryQuery
     */
    public function disableRoot()
    {
        return $this->andWhere([
            'AND',
            ['<>', $this->_alias . '.depth', 0],
            ['<>', $this->_alias . '.id', 1]
        ]);
    }



    /**
     * @return ProductCategoryQuery
     */
    public function withProduct()
    {
        return $this->joinWith([
            'products product' => function (ActiveQuery $q) {
                $q->addSelect([
                    'product.id',
                    'product.category_id'
                ]);
            }]);
    }
}