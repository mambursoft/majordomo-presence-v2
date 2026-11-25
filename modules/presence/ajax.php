<?php
header('Content-Type: application/json; charset=utf-8');
chdir(dirname(__FILE__) . '/../../');
include_once('./config.php');
include_once('./lib/loader.php');
include_once('./load_settings.php');
$session = new session('prj');
include_once(DIR_MODULES . 'control_access/control_access.class.php');
$access = new control_access();
if (!$access->checkAccess('presence')) {
    http_response_code(403);
    echo json_encode(array('status' => 'error', 'msg' => 'Access denied'));
    exit;
}
include_once(DIR_MODULES . 'presence/presence.class.php');
$module = new presence();
$payload = json_decode((string)(isset($_POST['payload']) ? $_POST['payload'] : ''), true);
if (!is_array($payload)) {
    echo json_encode(array('status' => 'error', 'msg' => 'Invalid payload'));
    exit;
}
echo json_encode($module->api($payload));