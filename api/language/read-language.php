<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Language.php';

$db = new Database;
$conn = $db->connect();
$lang = new Language($conn);

$lang->language = $_GET['lang'];

$result = $lang->read_lang();

$num = $result->rowCount();

if($num > 0){
    $language_arr = array();
    $row = $result->fetch();
    $language_arr['data'] = array(
        'languageId' => $row->id,
        'language' => $row->language,
    );
    echo json_encode($language_arr);
}
else {
    echo json_encode(array(
        'message' => 'No Match Found'
    ));
}