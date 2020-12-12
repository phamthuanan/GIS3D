<?php
include_once dirname(__FILE__) . '/app/BaseConverter.php';
include_once dirname(__FILE__) . '/app/config.php';
include_once dirname(__FILE__) . '/app/Connection.php';
include_once dirname(__FILE__) . '/app/GraphicUtil.php';
include_once dirname(__FILE__) . '/app/NetworkJsonConverter.php';
function print_json_response($object) {
    header ( 'Content-Type: application/json' );
    return json_encode($object);
}
$name = $_REQUEST['name'];
$hospital = $_REQUEST['hospital'];
$house = $_REQUEST['house'];
switch ($name) {
    case 'network_json':
        $converter = new NetworkJsonConverter();
        echo print_json_response($converter->getJsonData($hospital,$house));
        break;
    default:
        http_response_code(404);
}
