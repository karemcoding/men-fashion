<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 1:03 AM 4/28/2021
 * @projectName baseProject by ANDY
 */

namespace common\widgets\toastr;

use Yii;
use yii\bootstrap4\Widget;

/**
 * Class ToastrAlert
 * @package common\widgets\toastr
 */
class ToastrAlert extends Widget
{
    public $alertTypes = [
        'error' => Toastr::ERROR,
        'success' => Toastr::SUCCESS,
        'info' => Toastr::INFO,
        'warning' => Toastr::WARNING,
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            foreach ((array)$flash as $i => $message) {
                echo Toastr::widget([
                    'type' => $this->alertTypes[$type],
                    'message' => $message,
                    'pluginOptions' => [
                        'closeButton' => true,
                        'positionClass' => 'toast-bottom-right',
                        'timeOut' => 0,
                        'extendedTimeOut' => 0,
                        'tapToDismiss' => false
                    ],
                ]);
            }

            $session->removeFlash($type);
        }
    }
}