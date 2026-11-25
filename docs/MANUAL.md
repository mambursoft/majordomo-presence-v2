# Manual

## Ukrainian setup

The module is designed for a local MQTT broker on a Raspberry Pi or mini-PC, so it keeps working during internet outages and does not depend on a VPN or cloud service. Set the MajorDoMo time zone to `Europe/Kyiv` — the `Updated` column uses local time from the system.

Example BLE tag names for Ukrainian homes: `tag-front-door=Вхідні двері`, `tag-kitchen=Кухня`, `tag-garage=Гараж`.

## MQTT payload

Publish from your BLE scanner to any local topic; the bridge POSTs JSON:

```json
{"uuid":"tag-front-door","rssi":-62}
```

The module writes `Presence.<uuid>.active`, `.updated` and `.rssi` properties.

## Scenario API

```php
$p = getModule('presence');
$result = $p->api(array('uuid' => 'tag-front-door', 'rssi' => -62));
if ($result['present']) {
  say('Вітаємо вдома');
}
```

## Offline behaviour

All state is stored in MajorDoMo properties (`gg`/`sg`). If the scanner or broker is temporarily unavailable, the last known state remains until the configured timeout expires — no cloud lookup is needed.
