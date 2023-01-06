<?php

namespace common\behaviors\audit;

use PHPUnit\Framework\Constraint\Callback;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\db\Connection;
use yii\db\Exception;
use yii\web\Application as WebApp;

/**
 * Class AuditBehavior
 * @package common\util
 */
class AuditBehavior extends Behavior
{
    const OP_UPDATE = 'UPDATE';
    const OP_INSERT = 'INSERT';
    const OP_SOFT_DELETE = 'SOFT_DELETE';
    const OP_HARD_DELETE = 'HARD_DELETE';

    /**
     * @var ActiveRecord the owner of this behavior.
     */
    public $owner;

    /**
     * @var array list of attributes that should not store logdata.
     */
    public $except = ['updated_at'];

    /**
     * @var string name of the DB table to store log content. Defaults to "record_audit".
     */
    public $tableName = '{{%audit}}';

    /**
     * @var Callback
     */
    public $softDeleteWhen;

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
            ActiveRecord::EVENT_AFTER_DELETE => 'catchDelete',
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
                        $operator = self::OP_UPDATE;
                        if (is_callable($this->softDeleteWhen)) {
                            if (call_user_func($this->softDeleteWhen, $this->owner)) {
                                $operator = self::OP_SOFT_DELETE;
                            }
                        }
                        yield [
                            'operation' => $operator,
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

    /**
     * @param $event
     * @throws Exception
     * Biến $time Đảm bảo rằng: 1 owner thay đổi thì có nhiều record trong audit dc thêm vào, tùy
     * thuộc vào số lượng attribute của owner thay đổi bao nhiêu cái,nên có thể thời gian thêm vào giữa các record trong
     * audit khác nhau, như vậy là sai. Nên dùng biến $time để đảm bảo chúng cùng thời gian.
     */
    public function catchDelete($event)
    {
        $newAttributes = $this->owner->getAttributes();
        $time = time();
        $gen = function () use ($newAttributes, $time) {
            foreach ($newAttributes as $key => $value) {
                yield [
                    'operation' => self::OP_HARD_DELETE,
                    'model' => get_class($this->owner),
                    'record_pk' => $this->owner->primaryKey,
                    'attribute' => $key,
                    'old' => $value,
                    'new' => NULL,
                    'timestamp' => $time,
                    'identity' => $this->identity,
                    'identity_id' => $this->identityId,
                    'identity_ip' => $this->identityIp,
                ];
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