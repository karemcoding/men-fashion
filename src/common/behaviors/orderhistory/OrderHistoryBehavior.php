<?php

namespace common\behaviors\orderhistory;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\db\Connection;
use yii\db\Exception;
use yii\web\Application as WebApp;

/**
 * Class OrderHistoryBehavior
 * @package common\behaviors\orderhistory
 */
class OrderHistoryBehavior extends Behavior
{
    const OP_UPDATE = 'UPDATE';
    const OP_INSERT = 'INSERT';

    /**
     * @var ActiveRecord the owner of this behavior.
     */
    public $owner;

    /**
     * @var string name of the DB table to store log content. Defaults to "record_audit".
     */
    public $tableName = '{{%order_history}}';

    /**
     * @var array
     */
    public $only = ['status', 'payment_status', 'payment_method', 'remark'];

    /**
     * @var array list of attributes that should not store logdata.
     */
    public $except = ['updated_at'];

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var string|null
     */
    protected $identity = NULL;

    /**
     * @var int|null
     */
    protected $identityId = NULL;

    /**
     * @var string
     */
    protected $identityIp;

    public function init()
    {
        parent::init();
        $this->setIdentity();
        $this->db = Yii::$app->getDb();
    }

    /**
     * @return string[]
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'catchInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'catchUpdate',
        ];
    }

    /**
     * @param AfterSaveEvent $event
     * @throws Exception
     */
    public function catchInsert(AfterSaveEvent $event)
    {
        $newAttributes = $this->owner->getAttributes();
        $time = time();
        $gen = function () use ($newAttributes, $time) {
            foreach ($newAttributes as $key => $value) {
                if (in_array($key, $this->only)) {
                    yield [
                        'operation' => self::OP_INSERT,
                        'model' => get_class($this->owner),
                        'record_pk' => $this->owner->primaryKey,
                        'attribute' => $key,
                        'old' => NULL,
                        'new' => $value,
                        'timestamp' => $time,
                        'identity' => $this->identity,
                        'identity_id' => $this->identityId,
                        'identity_ip' => $this->identityIp,
                    ];
                }
            }
        };
        $this->saveAuditRecord($gen());
    }

    /**
     * @param AfterSaveEvent $event
     * @throws Exception
     */
    public function catchUpdate(AfterSaveEvent $event)
    {
        $oldAttributes = $event->changedAttributes;
        $newAttributes = $this->owner->getAttributes();
        $time = time();
        $gen = function () use ($oldAttributes, $newAttributes, $time) {
            foreach (array_keys($oldAttributes) as $name) {
                if ($oldAttributes[$name] != $newAttributes[$name]) {
                    if (!in_array($name, $this->except)) {
                        yield [
                            'operation' => self::OP_UPDATE,
                            'model' => get_class($this->owner),
                            'record_pk' => $this->owner->primaryKey,
                            'attribute' => $name,
                            'old' => $oldAttributes[$name],
                            'new' => $newAttributes[$name],
                            'timestamp' => $time,
                            'identity' => $this->identity,
                            'identity_id' => $this->identityId,
                            'identity_ip' => $this->identityIp,
                        ];
                    }
                }
            }
        };
        $this->saveAuditRecord($gen());
    }

    protected function setIdentity()
    {
        if (Yii::$app instanceof WebApp) {
            $this->identityIp = Yii::$app->request->getUserIP();
            if (!Yii::$app->user->getIsGuest()) {
                $user = Yii::$app->user->identity;
                $this->identity = get_class($user);
                $this->identityId = $user->getId();
            }
        }
    }

    /**
     * @return string[]
     */
    protected function attributeList()
    {
        return [
            'operation',
            'model', 'record_pk',
            'attribute',
            'old', 'new',
            'timestamp',
            'identity', 'identity_id', 'identity_ip',
        ];
    }

    /**
     * @param $gen
     * @throws Exception
     */
    protected function saveAuditRecord($gen)
    {
        $this->db->createCommand()
            ->batchInsert(
                $this->db->quoteTableName($this->tableName),
                $this->attributeList(),
                $gen)
            ->execute();
    }
}