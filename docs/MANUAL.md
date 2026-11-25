# Manual

1. Install the module and open **Control Panel → Presence v2**.
2. List tags as `uuid=Description`, one per line.
3. Set the RSSI threshold (default `-75`) and timeout in seconds.
4. Publish MQTT messages from your BLE scanner to any topic; the bridge POSTs JSON:

```json
{"uuid":"tag-kitchen","rssi":-62}
```

5. Call from a scenario:

```php
$p = getModule('presence');
$result = $p->api(array('uuid' => 'tag-kitchen', 'rssi' => -62));
```

The module writes `Presence.<uuid>.active`, `.updated` and `.rssi` properties. No cloud service is used.