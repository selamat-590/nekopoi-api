<?php
header('Content-type: application/json; charset=utf-8;');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
require_once('src/neko_class.php');
$_nekopoi = new PoiPoi\NekoPoi_;

//** Tambahkan parameter ?page= untuk ke halaman berikutnya
(isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? define('PAGE', $_REQUEST['page']) : define('PAGE', 1);

//* Atur domain NekoPoi yang Aktif (tidak terkena IPO)
$_nekopoi->setNekoDomain('nekopoi.bid');
//* Result JSON
$json = json_encode($_nekopoi->hentai_list(PAGE), JSON_PRETTY_PRINT); 
print $json;


?>
