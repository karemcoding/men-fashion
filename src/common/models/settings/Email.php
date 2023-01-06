<?php

namespace common\models\settings;

use Yii;

class Email extends Setting
{
    public $email_smtp_server;
    public $email_smtp_username;
    public $email_smtp_password;
    public $email_smtp_port;
    public $email_smtp_protocol;
    public $email_sender;
    public $email_sender_name;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email_smtp_server', 'email_smtp_username',
                'email_smtp_password', 'email_smtp_port', 'email_smtp_protocol',
                'email_sender', 'email_sender_name'], 'required'],
            [['email_smtp_server', 'email_smtp_username',
                'email_smtp_password', 'email_smtp_protocol',
                'email_sender', 'email_sender_name'], 'string'],
            [['email_smtp_port'], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email_smtp_server' => Yii::t('common', 'Server'),
            'email_smtp_username' => Yii::t('common', 'Username'),
            'email_smtp_password' => Yii::t('common', 'Password'),
            'email_smtp_port' => Yii::t('common', 'Port'),
            'email_smtp_protocol' => Yii::t('common', 'Protocol'),
            'email_sender' => Yii::t('common', 'Sender Email'),
            'email_sender_name' => Yii::t('common', 'Sender Name'),
        ];
    }
}