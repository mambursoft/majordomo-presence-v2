<?php
$dictionary=array(
'PV_RSSI'=>'Поріг RSSI',
'PV_TIMEOUT'=>'Таймаут, секунди',
'PV_TAGS'=>'BLE-мітки, по одному в рядку: uuid=опис',
'PV_TEST'=>'Тестовий пакет',
'PV_HINT'=>'Надсилайте {"uuid":"tag","rssi":-60} з MQTT-мосту. Модуль записує властивості Presence.<uuid>.active.',
'PV_ERROR'=>'Запит не виконано',
'PV_UUID'=>'UUID мітки',
'PV_TITLE'=>'Опис',
'PV_ACTIVE'=>'Вдома',
'PV_UPDATED'=>'Оновлено'
);
foreach($dictionary as $k=>$v){if(!defined('LANG_'.$k))define('LANG_'.$k,$v);}
