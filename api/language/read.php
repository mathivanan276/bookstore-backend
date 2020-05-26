<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Language.php';

$db = new Database;
$conn = $db->connect();
$lang = new Language($conn);

$result = $lang->read();

$num = $result->rowCount();

if($num > 0){
    $lang_arr = array();
    $lang_arr['data'] = array();
    $lang_items = array();

    while($row = $result->fetch()){
        $lang_items = array(
            'languageId' => $row->id,
            'language' => $row->language
        );
        array_push($lang_arr['data'],$lang_items);
    }
    echo json_encode($lang_arr);
}
else {
    echo json_encode(array(
        'message' => 'No Match Found'
    ));
}