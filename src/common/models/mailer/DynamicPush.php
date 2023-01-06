<?php

namespace common\models\mailer;

use common\models\Customer;
use common\models\CustomerGroup;
use common\util\mailer\MailerConsole;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class DynamicPush
 * @package common\models\mailer
 */
class DynamicPush extends Model
{
    const SINGLE_RECEIVER = 20;
    const GROUP_RECEIVER = 30;

    public $subject;

    public $body;

    public $receiver;

    public $receiver_id;

    public $member;

    public $group;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [[
                'subject', 'body',
                'receiver', 'receiver_id',
            ], 'required'],
            [['subject', 'body', 'member', 'group'], 'string'],
            [['receiver'], 'in', 'range' => [self::SINGLE_RECEIVER, self::GROUP_RECEIVER]],
            [['receiver_id'], 'exist',
                'targetClass' => Customer::class,
                'targetAttribute' => 'id',
                'when' => function (self $model) {
                    return $model->receiver == self::SINGLE_RECEIVER;
                },
            ],
            [['receiver_id'], 'exist',
                'targetClass' => CustomerGroup::class,
                'targetAttribute' => 'id',
                'when' => function (self $model) {
                    return $model->receiver == self::GROUP_RECEIVER;
                },
            ],
        ];
    }

    /**
     * @return bool|null
     */
    public function handle()
    {
        if (!$this->validate()) {
            return NULL;
        }
        if ($receiverMails = $this->handleReceiver()) {
            return MailerConsole::pushQueue($receiverMails, $this->subject, $this->body);
        }
        return NULL;
    }

    /**
     * @return array|null
     */
    protected function handleReceiver()
    {
        if ($this->receiver == self::SINGLE_RECEIVER) {
            $receiver = Customer::findAll(['id' => $this->receiver_id]);
        }
        if ($this->receiver == self::GROUP_RECEIVER) {
            $receiver = Customer::findAll(['group_id' => $this->receiver_id]);
        }
        if (!empty($receiver)) {
            return ArrayHelper::getColumn($receiver, 'email');
        }
        return NULL;
    }

    /**
     * @return array
     */
    public static function receiverSelect()
    {
        return [
            self::SINGLE_RECEIVER => Yii::t('common', 'Single Member'),
            self::GROUP_RECEIVER => Yii::t('common', 'Group Member'),
        ];
    }
}