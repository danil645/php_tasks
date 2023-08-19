<?php

namespace lib\Agents;


class Iblockk
{
    public static function clearOldLogs()
    {
        if (\Bitrix\Main\Loader::includeModule('iblock')) {
            //Сколько оставить элементов
            $COUNT_NOT_DELETE = 10;
            //Получение id инфоблока LOG
            $logIBlockId = \CIBlock::GetList([], ["CODE" => "LOG"]) > Fetch()["ID"];
            // ID инфоблока, из которого будем удалять элементы

            // Получаем ID элементов для удаления
            $idElementsToDelete = [];
            $dbItems = \CIBlockElement::GetList(
            //По дате изменения
                ["TIMESTAMP_X" => "DESC"],
                ["IBLOCK_ID" => $logIBlockId],
                false,
                ["nTopCount" => $COUNT_NOT_DELETE],
                ["ID"]
            );
            while ($item = $dbItems->Fetch()) {
                $idElementsToDelete[] = $item["ID"];
            }

            //Удаляем элементы
            //Получение элементов на удаление
            $dbItems = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => $logIBlockId, "!ID" => $idElementsToDelete],
                false,
                false,
                ["ID"]
            );
            //Удаление элементов по ID
            while ($item = $dbItems->Fetch()) {
                \CIBlockElement::Delete($item["ID"]);
            }
        }

        return '\\' . __CLASS__ . '::' . __FUNCTION__ . '();';
    }


    public static function example()
    {
        global $DB;
        if (\Bitrix\Main\Loader::includeModule('iblock')) {
            $iblockId = \Only\Site\Helpers\IBlock::getIblockID('QUARRIES_SEARCH', 'SYSTEM');
            $format = $DB->DateFormatToPHP(\CLang::GetDateFormat('SHORT'));
            $rsLogs = \CIBlockElement::GetList(['TIMESTAMP_X' => 'ASC'], [
                'IBLOCK_ID' => $iblockId,
                '<TIMESTAMP_X' => date($format, strtotime('-1 months')),
            ], false, false, ['ID', 'IBLOCK_ID']);
            while ($arLog = $rsLogs->Fetch()) {
                \CIBlockElement::Delete($arLog['ID']);
            }
        }
        return '\\' . __CLASS__ . '::' . __FUNCTION__ . '();';
    }
}
