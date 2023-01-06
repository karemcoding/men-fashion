<?php

namespace common\jobs;

use common\models\mailer\EmailHistory;
use common\util\mailer\MailerConsole;
use common\util\Status;
use Exception;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class MailerJob
 * @package common\jobs
 */
class MailerJob extends BaseObject implements JobInterface
{
    public $email_history_id;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty($this->email_history_id)) {
            throw new InvalidConfigException('Email History Id can not be blank.');
        }
    }

    /**
     * @param Queue $queue
     * @return mixed|void
     * @throws Exception
     */
    public function execute($queue)
    {
        try {
            $history = EmailHistory::findOne(['id' => $this->email_history_id]);
        } catch (Exception $exception) {
            echo "Error Found => " . $exception->getMessage() . "\n";
            throw $exception;
        }
        if ($history && $history->status == Status::STATUS_ACTIVE) {
            $receiverMail = explode(';', $history->receiver);
            $history->sent_at = time();
            try {
                MailerConsole::send($receiverMail, $history->subject, $history->content);
                echo "Email Id was sent : {$history->id}\n";
                $history->status = Status::STATUS_INACTIVE;
                $history->save();
            } catch (Exception $exception) {
                echo "Error Found => " . $exception->getMessage() . "\n";
                $history->save();
                throw $exception;
            }
        }
    }
}