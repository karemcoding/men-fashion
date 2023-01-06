<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 11:24 PM 5/10/2021
 * @projectName baseProject by ANDY
 */

namespace api\models;


use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;

/**
 * Class Cart
 * @package api\models
 */
class Cart extends \common\models\Cart
{
    /**
     * @return bool
     */
    public function add()
    {
        $currentCart = \common\models\Cart::findOne([
            'product_id' => $this->product_id,
            'customer_id' => $this->customer_id,
        ]);
        if ($currentCart) {
            $currentCart->quantity = $this->quantity;
            $currentCart->created_at = $this->created_at;
            return $currentCart->save();
        }
        return $this->save();
    }

    /**
     * @param array $data
     * @return false|int
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function multipleAdd($data = [])
    {
        Cart::deleteAll(['customer_id' => Yii::$app->user->identity->getId()]);
        $attributes = self::getTableSchema()->getColumnNames();
        if (self::validateMultiple($data, $attributes)) {
            return Yii::$app->db->createCommand()
                ->batchInsert(self::tableName(),
                    $attributes, $data)->execute();
        }

        return FALSE;
    }
}