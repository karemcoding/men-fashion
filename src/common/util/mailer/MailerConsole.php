<?php

namespace common\util\mailer;

use common\jobs\MailerJob;
use common\models\mailer\EmailHistory;
use common\models\settings\Email;
use common\util\AppHelper;
use Exception;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class Mailer
 * @package common\util
 */
class MailerConsole
{

    /**
     * @param $to
     * @param $subject
     * @param $body_html
     * @param string $body_plain
     * @param null $attachment
     * @param string $reply_to
     * @param array $ccs
     * @param array $bccs
     * @return bool
     * @throws InvalidConfigException
     */
    public static function send(
        $to,
        $subject,
        $body_html,
        $body_plain = '',
        $attachment = NULL,
        $reply_to = '',
        $ccs = [],
        $bccs = [])
    {
        try {
            /** @var Email $setting */
            $setting = Yii::$app->setting->get()->model(Email::class);

            /**@var \yii\swiftmailer\Mailer $mailer */
            $mailer = Yii::$app->mailer;
            $mailer->setTransport([
                'class' => 'Swift_SmtpTransport',
                'host' => $setting->email_smtp_server,
                'username' => $setting->email_smtp_username,
                'password' => $setting->email_smtp_password,
                'port' => $setting->email_smtp_port,
                'encryption' => $setting->email_smtp_protocol,
            ]);

            $mailer = $mailer->compose()
                ->setFrom([$setting->email_sender => $setting->email_sender_name])
                ->setTo($to)
                ->setSubject($subject)
                ->setTextBody($body_plain)
                ->setHtmlBody($body_html);

            if (!empty($reply_to)) {
                $mailer->setReplyTo($reply_to);
            }

            if (!empty($ccs)) {
                $mailer->setCc($ccs);
            }

            if (!empty($bccs)) {
                $mailer->setBcc($bccs);
            }

            if (!empty($attachment)) {
                if (!is_array($attachment)) {
                    $attachment = [$attachment];
                }

                foreach ($attachment as $attach) {
                    if (!empty($attach['content'])) {
                        $content = $attach['content'];
                        unset($attach['content']);
                        $mailer->attachContent($content, $attach);
                    } else {
                        $mailer->attach($attach);
                    }
                }
            }
            return $mailer->send();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $receiver
     * @param $subject
     * @param $body
     * @return bool
     */
    public static function pushQueue($receiver, $subject, $body)
    {
        $receiverResult = $receiver;
        if (is_array($receiver)) {
            $receiverResult = implode(';', $receiver);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $eh = new EmailHistory([
                'receiver' => $receiverResult,
                'subject' => $subject,
                'content' => $body,
                'sent_at' => NULL,
            ]);
            if ($eh->save()) {
                AppHelper::mailerQueue()->push(new MailerJob(['email_history_id' => $eh->id]));
            }
            $transaction->commit();
            return TRUE;
        } catch (Exception $exception) {
            $transaction->rollBack();
        }
        return FALSE;
    }
}
