<?php

namespace common\util\mailer;

use common\models\Customer;
use common\models\mailer\EmailContent;

/**
 * Class Alert
 * @package common\util\mailer
 */
final class Alert extends MailerConsole
{
    /**
     * @param Customer $customer
     * @return bool
     */
    public static function newUser(Customer $customer): bool
    {
        if ($email = EmailContent::findKey('new_member')) {
            $alert_params = $email_params = $email->templateObj->emailParams;
            $alert_params['user:name'] = $customer->name;
            $alert_params['user:email'] = $customer->email;
            $subject = str_replace($email_params, $alert_params, $email->subject);
            $body = str_replace($email_params, $alert_params, $email->content);
            return self::pushQueue($customer->email, $subject, $body);
        }
        return FALSE;
    }

    /**
     * @param Customer $customer
     * @param $code
     * @return bool
     */
    public static function validateCode(Customer $customer, $code): bool
    {
        if ($email = EmailContent::findKey('reset_password_get_code')) {
            $alert_params = $email_params = $email->templateObj->emailParams;
            $alert_params['user:name'] = $customer->name;
            $alert_params['code'] = $code;
            $subject = str_replace($email_params, $alert_params, $email->subject);
            $body = str_replace($email_params, $alert_params, $email->content);
            return self::pushQueue($customer->email, $subject, $body);
        }
        return FALSE;
    }

    /**
     * @param Customer $customer
     * @return bool
     */
    public static function changePassword(Customer $customer): bool
    {
        if ($email = EmailContent::findKey('change_password')) {
            $alert_params = $email_params = $email->templateObj->emailParams;
            $alert_params['user:name'] = $customer->name;
            $subject = str_replace($email_params, $alert_params, $email->subject);
            $body = str_replace($email_params, $alert_params, $email->content);
            return self::pushQueue($customer->email, $subject, $body);
        }
        return FALSE;
    }
}