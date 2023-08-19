<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/local/modules/dev.site/lib/Handlers/Iblock.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/local/modules/dev.site/lib/Agents/Iblockk.php";

use lib\Handlers\Iblock;
use lib\Agents\Iblockk;

$IblockHandler = new Iblock();

//Подключение обработчиков
AddEventHandler("iblock", "OnAfterIBlockElementAdd", array($IblockHandler, "addLog"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array($IblockHandler, "addLog"));

CAgent::AddAgent(
    "\\lib\\Agents\\Iblockk::clearOldLogs();",
    "dev.site",
    "N",
    3600,
    date("d.m.Y H:i:s"),
    "Y",
    date("d.m.Y H:i:s"),
    30
);
?>


