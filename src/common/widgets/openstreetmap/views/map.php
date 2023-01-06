<?php
/**
 * @var View $this
 * @var float $default_lat
 * @var float $default_lng
 * @var int $height
 * @var int $scale
 * @var string $url
 * @var string $url_reverse
 * @var string $input_lat_id
 * @var string $input_lng_id
 * @var string $lat_val
 * @var string $lng_val
 * @var string $input_address_id
 * @var string $button_search_id
 * @var string $label_address
 * @var bool $drag_marker
 */

use common\widgets\openstreetmap\OpenStreetMapAsset;
use yii\web\View;

OpenStreetMapAsset::register($this);
?>
    <div id="map" class="w-100" style="height: <?= $height ?>px"></div>
<?php
$lat_val = (float)$lat_val;
$lng_val = (float)$lng_val;
$js = <<<JS
    var map_obj = null;
    var location_current = [$default_lat,$default_lng]
    if($lat_val && $lng_val){
        location_current = [$lat_val,$lng_val]
    }
	
    map_obj = L.map('map', {attributionControl: false}).setView(location_current, $scale)
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map_obj);
    var marker = new L.marker(location_current, {draggable: "{$drag_marker}"});
    map_obj.addLayer(marker);
    // Disable mousewheel zoom
    // map_obj.scrollWheelZoom.disable();
    map_obj.addControl(new L.Control.Fullscreen());
    
    marker.on('dragend', function(event) {
        let position = event.target.getLatLng();
        $.ajax({
            type: "POST",
            url: "{$url_reverse}",
            dataType: "json",
            data: {lat: position.lat, lng: position.lng }
        }).done(function(response) {
            if (response.code === 200){
                $("#{$input_address_id}").val(response.address);
                $("#{$input_lat_id}").val(response.lat);
                $("#{$input_lng_id}").val(response.lng);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
      
        })
    });
    
    $("#{$button_search_id}").on('click',function() {
        var address = $("#{$input_address_id}").val();
        $.ajax({
            url: "{$url}",
            type: 'POST',
            data: {q: address}
        }).done(function(response) {
            if (response.code === 200){
                marker.setLatLng([response.lat,response.lng], {draggable: 'true'}).update();
                map_obj.setView(marker.getLatLng(),$scale)
                $("#{$input_address_id}").val(response.address);
                $("#{$input_lat_id}").val(response.lat);
                $("#{$input_lng_id}").val(response.lng);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
          
        })
    });
JS;
$this->registerJs($js, View::POS_READY, 'open_street_map');