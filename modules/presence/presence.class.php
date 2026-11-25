<?php
/**
 * Presence v2 - local BLE/beacon presence from MQTT.
 * PHP 7.3 compatible, no external runtime dependencies.
 */
class presence extends module
{
    function __construct()
    {
        $this->name = 'presence';
        $this->title = 'Presence v2';
        $this->module_category = '<#LANG_SECTION_APPLICATIONS#>';
        $this->checkInstalled();
    }

    function getConfig()
    {
        include_once(ROOT . 'config.php');
        $this->config = array();
        $rec = SQLSelectOne("SELECT ID, DATA FROM modules WHERE NAME='" . $this->name . "'");
        $this->module_id = isset($rec['ID']) ? (int)$rec['ID'] : 0;
        if (!empty($rec['DATA'])) {
            $this->config = @unserialize($rec['DATA']);
        }
        if (!is_array($this->config)) {
            $this->config = array();
        }
        if (!isset($this->config['RSSI_MIN'])) {
            $this->config['RSSI_MIN'] = -75;
        }
        if (!isset($this->config['TIMEOUT'])) {
            $this->config['TIMEOUT'] = 20;
        }
        if (!isset($this->config['TAGS'])) {
            $this->config['TAGS'] = '';
        }
    }

    function saveConfig()
    {
        if (!$this->module_id) {
            $this->getConfig();
        }
        if (!$this->module_id) {
            return;
        }
        SQLUpdate('modules', array('ID' => $this->module_id, 'DATA' => serialize($this->config)));
    }

    function admin(&$out)
    {
        $this->getConfig();
        $out['RSSI_MIN'] = $this->config['RSSI_MIN'];
        $out['TIMEOUT'] = $this->config['TIMEOUT'];
        $out['TAGS'] = $this->config['TAGS'];
        $out['ROWS'] = array();
        foreach ($this->parseTags() as $uuid => $title) {
            $slug = $this->slug($uuid);
            $out['ROWS'][] = array(
                'UUID' => $uuid,
                'TITLE' => $title,
                'ACTIVE' => (int)gg('Presence.' . $slug . '.active'),
                'UPDATED' => (int)gg('Presence.' . $slug . '.updated')
            );
        }

        if ($this->mode == 'update') {
            $rssi = (int)gr('rssi_min');
            $timeout = (int)gr('timeout');
            $this->config['RSSI_MIN'] = ($rssi >= -120 && $rssi <= 0) ? $rssi : -75;
            $this->config['TIMEOUT'] = ($timeout >= 5 && $timeout <= 3600) ? $timeout : 20;
            $this->config['TAGS'] = trim((string)gr('tags'));
            $this->saveConfig();
            $this->redirect('?');
        }
    }

    function usual(&$out)
    {
        $this->admin($out);
    }

    function slug($uuid)
    {
        return preg_replace('/[^A-Za-z0-9_-]/', '_', (string)$uuid);
    }

    function parseTags()
    {
        $result = array();
        $lines = preg_split('/\r?\n/', $this->config['TAGS']);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '=') === false) {
                continue;
            }
            list($uuid, $title) = explode('=', $line, 2);
            $uuid = trim($uuid);
            if ($uuid !== '') {
                $result[$uuid] = trim($title);
            }
        }
        return $result;
    }

    function api($params)
    {
        $this->getConfig();
        $uuid = isset($params['uuid']) ? trim((string)$params['uuid']) : '';
        $rssi = isset($params['rssi']) ? (int)$params['rssi'] : -999;
        if ($uuid === '') {
            return array('status' => 'error', 'msg' => 'No tag uuid');
        }
        $known = $this->parseTags();
        if ($known && !isset($known[$uuid])) {
            return array('status' => 'error', 'msg' => 'Unknown tag');
        }
        $slug = $this->slug($uuid);
        $present = ($rssi >= (int)$this->config['RSSI_MIN']) ? 1 : 0;
        sg('Presence.' . $slug . '.active', $present);
        sg('Presence.' . $slug . '.updated', time());
        sg('Presence.' . $slug . '.rssi', $rssi);
        return array(
            'status' => 'ok',
            'uuid' => $uuid,
            'present' => $present,
            'rssi' => $rssi,
            'property' => 'Presence.' . $slug . '.active'
        );
    }

    function install($data = '')
    {
        parent::install();
    }

    function dbInstall($data)
    {
        return '';
    }
}
