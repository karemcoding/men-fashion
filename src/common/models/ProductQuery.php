<?php

namespace common\models;

use common\util\Status;

/**
 * This is the ActiveQuery class for [[Product]].
 *
 * @see Product
 */
class ProductQuery extends ActiveQuery
{
    public function init()
    {
        parent::init();
        $this->distinct()->withDiscountPrice();
    }

    protected function withDiscountPrice()
    {
        return $this->joinWith(['discounts'], FALSE)
            ->joinWith(['category category', 'productDiscounts' => function ($q) {
                $q->joinWith(['discount'])
                    ->andOnCondition([
                        ProductDiscount::$alias . '.status' => Status::STATUS_ACTIVE,
                        Discount::$alias . '.status' => Status::STATUS_ACTIVE,
                    ])->andOnCondition([
                        'AND',
                        ['<=', Discount::$alias . '.from', time()],
                        ['>=', Discount::$alias . '.to', time()],
                    ]);
            }]);
    }

    /**
     * @return ProductQuery
     */
    public function withCategory()
    {
        return $this->joinWith([
            'category category' => function (ActiveQuery $q) {
                $q->addSelect([
                    'category.id',
                    'category.name',
                    'category.thumbnail',
                ]);
            }
        ]);
    }

    /**
     * @return ProductQuery
     */
    public function selectDefault()
    {
        return $this->alias('this')->select([
            'this.id',
            'this.name',
            'this.enname',
            'this.jpname',
            'this.thumbnail',
            'this.price',
            'this.category_id',
            'this.inventory',
            'this.sold',
            'this.size',
            'this.hot',
            'this.created_at',
            'score' => 'SUM(feedbacks.score)/COUNT(feedbacks.id)'
        ])->joinWith(['feedbacks feedbacks'], FALSE)
            ->addGroupBy(['this.id'])

            ->orderBy(['this.id' => SORT_DESC]);
    }
}
