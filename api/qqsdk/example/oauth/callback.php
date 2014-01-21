<?php
require_once("../../API/qqConnectAPI.php");
$qc = new QC();
echo $qc->qq_callback();
echo "------";
echo $qc->get_openid();
//$qc = new QC();
$ret = $qc->get_info();
print_r($ret);