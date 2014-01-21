<?php

require_once("../../API/qqConnectAPI.php");
$qc = new QC();
$url = $qc->qq_login();
echo $url;
