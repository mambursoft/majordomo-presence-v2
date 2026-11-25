<?php
$dictionary=array(
'PV_RSSI'=>'Порог RSSI',
'PV_TIMEOUT'=>'Таймаут, секунды',
'PV_TAGS'=>'BLE-метки, по одной в строке: uuid=описание',
'PV_TEST'=>'Тестовый пакет',
'PV_HINT'=>'Передавайте {"uuid":"tag","rssi":-60} из MQTT-моста. Модуль записывает свойства Presence.<uuid>.active.',
'PV_ERROR'=>'Запрос не выполнен',
'PV_UUID'=>'UUID метки',
'PV_TITLE'=>'Описание',
'PV_ACTIVE'=>'Дома',
'PV_UPDATED'=>'Обновлено'
);
foreach($dictionary as $k=>$v){if(!defined('LANG_'.$k))define('LANG_'.$k,$v);}
