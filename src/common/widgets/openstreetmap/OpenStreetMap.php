<?php

namespace common\widgets\openstreetmap;

use yii\bootstrap4\Widget;

/**
 * Class OpenStreetMap
 *
 * @package common\modules\landingpage\widgets\openstreetmap
 */
class OpenStreetMap extends Widget
{

    public $default_lat = 1.3408630000000001;

    public $default_lng = 103.83039182212079;

    public $height = 320;

    public $url = '';

    public $url_reverse = '';

    public $scale = 13;

    public $input_lat_id = 'input_lat';

    public $input_lng_id = 'input_lng';

    public $lat_val;

    public $lng_val;

    public $input_address_id = 'input_address';

    public $label_address = '';

    public $button_search_id = '';

    public $drag_marker = TRUE;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('map', [
            'default_lat' => $this->default_lat,
            'default_lng' => $this->default_lng,
            'height' => $this->height,
            'url' => $this->url,
            'url_reverse' => $this->url_reverse,
            'scale' => $this->scale,
            'input_lat_id' => $this->input_lat_id,
            'input_lng_id' => $this->input_lng_id,
            'lat_val' => $this->lat_val,
            'lng_val' => $this->lng_val,
            'input_address_id' => $this->input_address_id,
            'label_address' => $this->label_address,
            'button_search_id' => $this->button_search_id,
            'drag_marker' => $this->drag_marker,
        ]);
    }
}