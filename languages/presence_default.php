<?php
$dictionary=array(
'PV_RSSI'=>'RSSI threshold',
'PV_TIMEOUT'=>'Timeout, seconds',
'PV_TAGS'=>'BLE tags, one per line: uuid=description',
'PV_TEST'=>'Test payload',
'PV_HINT'=>'Publish {"uuid":"tag","rssi":-60} from your MQTT bridge. The module writes Presence.<uuid>.active properties.',
'PV_ERROR'=>'Request failed',
'PV_UUID'=>'Tag UUID',
'PV_TITLE'=>'Description',
'PV_ACTIVE'=>'At home',
'PV_UPDATED'=>'Updated'
);
foreach($dictionary as $k=>$v){if(!defined('LANG_'.$k))define('LANG_'.$k,$v);}
