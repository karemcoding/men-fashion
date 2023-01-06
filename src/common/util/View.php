<?php

namespace common\util;

use Yii;
use yii\base\Event;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\Response;

/**
 * Class View
 * @package common\util
 */
class View extends \yii\web\View
{

    /**
     * @var AssetBundle
     */
    public $asset;

    /**
     * Enable or disable compression, by default compression is enabled.
     *
     * @var bool
     */
    public $compress = TRUE;

    /**
     * @param $message
     * @param array $params
     *
     * @return string
     */
    public function t($message, $params = [])
    {
        return Yii::t('common', $message, $params);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->compress === TRUE) {
            Yii::$app->response->on(Response::EVENT_BEFORE_SEND,
                function (Event $event) {
                    $response = $event->sender;
                    if ($response->format === Response::FORMAT_HTML) {
                        if (!empty($response->data)) {
                            $response->data = self::compress($response->data);
                        }
                        if (!empty($response->content)) {
                            $response->content = self::compress($response->content);
                        }
                    }
                });
        }
    }

    /**
     * HTML compress function.
     *
     * @param $html
     *
     * @return mixed
     */
    public static function compress($html)
    {
        // Searching textarea and pre
        preg_match_all('#\<textarea.*\>.*\<\/textarea\>#Uis', $html, $textarea);
        preg_match_all('#\<pre.*\>.*\<\/pre\>#Uis', $html, $pre);

        // replacing both with <textarea>$index</textarea> / <pre>$index</pre>
        $buffer = str_replace($textarea[0],
            array_map(function ($el) {
                return '<textarea>' . $el . '</textarea>';
            },
                array_keys($textarea[0])), $html);
        $buffer = str_replace($pre[0],
            array_map(function ($el) {
                return '<pre>' . $el . '</pre>';
            }, array_keys($pre[0])),
            $buffer);

        $filters = [
            // remove javascript comments
            '/(?:<script[^>]*>|\G(?!\A))(?:[^\'"\/<]+|"(?:[^\\"]+|\\.)*"|\'(?:[^\\\']+|\\.)*\'|\/(?!\/)|<(?!\/script))*+\K\/\/[^\n|<]*/xsu' => '',
            // remove html comments except IE conditions
            '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/su' => '',
            // remove comments in the form /* */
            '/\/+?\s*\*[\s\S]*?\*\s*\/+/u' => '',
            // shorten multiple white spaces
            '/>\s{2,}</u' => '><',
            // shorten multiple white spaces
            '/\s{2,}/u' => ' ',
            // collapse new lines
            '/(\r?\n)/u' => '',
        ];

        $buffer = preg_replace(array_keys($filters), array_values($filters), $buffer);

        // Replacing back with content
        $buffer = str_replace(array_map(function (
            $el) {
            return '<textarea>' . $el . '</textarea>';
        }, array_keys($textarea[0])), $textarea[0], $buffer);
        $buffer = str_replace(array_map(function ($el) {
            return '<pre>' . $el . '</pre>';
        },
            array_keys($pre[0])), $pre[0], $buffer);

        return $buffer;
    }

    /**
     * @param $route
     *
     * @return string|null
     */
    public function activeRoute($route)
    {
        $current_route = Url::toRoute($this->context->route);
        if (is_array($route)) {
            foreach ($route as $item) {
                if ($current_route === Url::toRoute($item)) {
                    return ' active';
                }
            }
            return null;
        }
        if ($current_route === Url::toRoute($route)) {
            return ' active';
        }
        return NULL;
    }

    /**
     * @param $controller
     * @return string|array
     */
    public function isController($controller)
    {
        if (is_array($controller)) {
            foreach ($controller as $item) {
                if ($this->context->id === $item) return true;
            }
        }
        return $this->context->id === $controller;
    }
}
