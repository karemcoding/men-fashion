<?php

namespace common\behaviors\auditrecord;

use Closure;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Exception;

class AuditBehavior extends Behavior
{
    /**
     * @var ActiveRecord the owner of this behavior.
     */
    public $owner;

    /**
     * @var array list of attributes that should not store logdata.
     */
    public $except = [];

    /**
     * The handled operations
     * Possible values are [[OP_INSERT]], [[OP_UPDATE]] and [[OP_DELETE]].
     * @var integer
     */
    public $operations = ActiveRecord::OP_ALL;

    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = 'common\behaviors\auditrecord\JsonSerializer';

    /**
     * The `created_at` field value
     * In case, when the value is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
     * will be used as value.
     */
    public $createdAt;

    /**
     * @var string name of the DB table to store log content. Defaults to "record_audit".
     */
    public $tableName = '{{%history}}';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'store',
            ActiveRecord::EVENT_AFTER_UPDATE => 'store',
            ActiveRecord::EVENT_AFTER_DELETE => 'store'
        ];
    }

    /**
     * @param $event
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function store($event)
    {
        switch ($event->name) {
            case ActiveRecord::EVENT_AFTER_INSERT:
                $operation = ActiveRecord::OP_INSERT;
                break;

            case ActiveRecord::EVENT_AFTER_UPDATE:
                $operation = ActiveRecord::OP_UPDATE;
                break;

            default:
                $operation = ActiveRecord::OP_DELETE;
        }

        if (!($operation & $this->operations)) {
            return;
        }

        $update = $operation === ActiveRecord::OP_UPDATE;
        $attributes = ($update) ? $event->changedAttributes : $this->owner->getAttributes();
        $attributeNames = array_keys($attributes);

        foreach ($attributeNames as $name) {
            if (in_array($name, $this->except)) {
                unset($attributes[$name]);
            }
        }

        $user = Yii::$app->getUser();
        if ($user->getIsGuest() || empty($attributes)) {
            return;
        }

        $data = [];
        foreach ($attributes as $name => $old) {
            $value = $this->owner->{$name};
            $changed = $old != $value;

            if ($operation === ActiveRecord::OP_INSERT || $changed) {
                $data[$name] = ['new' => $value];

                if ($update) {
                    $data[$name]['old'] = $old;
                }
            } elseif ($operation === ActiveRecord::OP_DELETE) {
                $data[$name] = ['old' => $value];
            }
        }

        if ($data) {
            $db = Yii::$app->getDb();
            $tableName = $db->quoteTableName($this->tableName);
            $columns = [
                'operation' => $operation,
                'model' => get_class($this->owner),
                'record_id' => $this->owner->id,
                'data' => $this->serializeData($data),
                'created_by' => $user->id,
                'created_at' => $this->extractCreatedAt(),
            ];
            $db->createCommand()
                ->insert($tableName, $columns)
                ->execute();
        }

    }

    /**
     * @param $data
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function serializeData($data)
    {
        return Yii::createObject($this->serializer)->serialize($data);
    }

    /**
     * Returns the value for created_at property.
     * to the attributes corresponding to the triggering event.
     * @return mixed the attribute value
     */
    protected function extractCreatedAt()
    {
        if ($this->createdAt === null) {
            return time();
        }

        if ($this->createdAt instanceof Closure || (is_array($this->createdAt) && is_callable($this->createdAt))) {
            return call_user_func($this->createdAt, $this);
        }

        return $this->createdAt;
    }
}
