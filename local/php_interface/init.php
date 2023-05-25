<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;

if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/modules/devsite/lib/Handlers/Iblock.php")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/devsite/lib/Handlers/Iblock.php");
}
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("Iblock", "createLogEntry"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("Iblock", "updateLogEntry"));
\Bitrix\Main\Loader::includeModule('iblock');

if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/modules/devsite/lib/Agents/Iblock.php")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/devsite/lib/Agents/Iblock.php");
}
$agentExists = false;
$agentList = \CAgent::GetList([], ['NAME' => 'Iblock::clearOldLogs();']);
while ($agent = $agentList->Fetch()) {
    if ($agent['MODULE_ID'] === 'devsite') {
        $agentExists = true;
        break;
    }
}
// Проверка наличия агента перед его добавлением
if (!\CAgent::GetList([], ['NAME' => 'Iblock::clearOldLogs();'])->Fetch()) {
 \CAgent::Add([
    'NAME' => 'Iblock::clearOldLogs();',
    'MODULE_ID' => 'devsite',
    'ACTIVE' => 'Y',
    'NEXT_EXEC' => DateTime::createFromTimestamp(strtotime('+1 hour')),
    'AGENT_INTERVAL' => 3600,
]);
};