<?php

namespace lib\Handlers;

class Iblock
{
    function addLog(&$arFields)
    {
        //Получение текущего iblock
        $arIBlock = \CIBlock::GetByID($arFields["IBLOCK_ID"])->Fetch();;
        //Код текущего iblock
        $iblockCode = $arIBlock["CODE"];
        // Исключаем обработку элементов из iblock LOG
        if ($iblockCode == "LOG") {
            return;
        }

        //Получение id инфоблока LOG
        $logIblock = \CIBlock::GetList([], ["CODE" => "LOG"]);
        $logIBlockId = $logIblock->Fetch()["ID"];
        // Создание/получаем раздел в инфоблоке LOG с именем и кодом текущего инфоблока
        if (!$logIBlockId) {
            // Создание инфоблока LOG
            $logIBlockFields = [
                "ACTIVE" => "Y",
                "NAME" => "Лог изменений",
                "CODE" => "LOG",
            ];
            $ib = new CIBlock;
            $logIBlockId = $ib->Add($logIBlockFields);
        }

        // Получаем/создаем раздел в инфоблоке LOG с именем и кодом текущего инфоблока
        $section = \CIBlockSection::GetList([], ["IBLOCK_ID" => $logIBlockId, "CODE" => $iblockCode])->Fetch();
        if (!$section) {
            $section = new \CIBlockSection;
            $sectionFields = [
                "IBLOCK_ID" => $logIBlockId,
                "NAME" => $arIBlock["NAME"],
                "CODE" => $iblockCode,
            ];
            $sectionId = $section->Add($sectionFields);
        } else {
            $sectionId = $section["ID"];
        }

        //Есть ли элемент с таким названием в iblock LOG
        $existingElement = \CIBlockElement::GetList([], [
            "IBLOCK_ID" => $logIBlockId,
            "IBLOCK_SECTION_ID" => $sectionId,
            "NAME" => $arFields["ID"],
        ])->Fetch();

        //Получение пути разделов от родительского к дочернему
        $navChain = \CIBlockSection::GetNavChain(
        //ID текущего Iblockk
            $arFields["IBLOCK_ID"],
            //Раздел в котором элемент
            $arFields["IBLOCK_SECTION"][0],
            //Возвращаем только NAME
            array("NAME"),
            //В виде массива
            true,
        );
        $sectionPath = "";
        foreach ($navChain as $section) {
            //Забираем только NAME
            $sectionPath .= $section["NAME"] . " -> ";
        }
        //Формируем строку для описания анонса
        $previewText = $arIBlock["NAME"] . " -> " . $sectionPath . $arFields["NAME"];

        //Получаем дату изменения текущего элемента
        $elementDateEdit = \CIBlockElement::GetList([], [
            "IBLOCK_ID" => $arIBlock["IBLOCK_ID"],
            "ID" => $arFields["ID"],
        ])->Fetch()["TIMESTAMP_X"];

        //Создание нового элемента для заполнения полей
        $el = new \CIBlockElement;
        $elementFields = [
            "IBLOCK_ID" => $logIBlockId,
            "IBLOCK_SECTION_ID" => $sectionId,
            "NAME" => $arFields["ID"],
            // Начало активности
            "ACTIVE_FROM" => $elementDateEdit,
            //Описание для анонса
            "PREVIEW_TEXT" => $previewText,
        ];

        // Если элемент существует - обновляем, иначе добавляем новый
        if ($existingElement) {
            $elementId = $existingElement["ID"];
            $el->Update($elementId, $elementFields);
        } else {
            $elementId = $el->Add($elementFields);
        }
    }

    function OnBeforeIBlockElementAddHandler(&$arFields)
    {
        $iQuality = 95;
        $iWidth = 1000;
        $iHeight = 1000;
        /*
         * Получаем пользовательские свойства
         */
        $dbIblockProps = \Bitrix\Iblock\PropertyTable::getList(array(
            'select' => array('*'),
            'filter' => array('IBLOCK_ID' => $arFields['IBLOCK_ID'])
        ));
        /*
         * Выбираем только свойства типа ФАЙЛ (F)
         */
        $arUserFields = [];
        while ($arIblockProps = $dbIblockProps->Fetch()) {
            if ($arIblockProps['PROPERTY_TYPE'] == 'F') {
                $arUserFields[] = $arIblockProps['ID'];
            }
        }
        /*
         * Перебираем и масштабируем изображения
         */
        foreach ($arUserFields as $iFieldId) {
            foreach ($arFields['PROPERTY_VALUES'][$iFieldId] as &$file) {
                if (!empty($file['VALUE']['tmp_name'])) {
                    $sTempName = $file['VALUE']['tmp_name'] . '_temp';
                    $res = \CAllFile::ResizeImageFile(
                        $file['VALUE']['tmp_name'],
                        $sTempName,
                        array("width" => $iWidth, "height" => $iHeight),
                        BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                        false,
                        $iQuality);
                    if ($res) {
                        rename($sTempName, $file['VALUE']['tmp_name']);
                    }
                }
            }
        }

        if ($arFields['CODE'] == 'brochures') {
            $RU_IBLOCK_ID = \Only\Site\Helpers\IBlock::getIblockID('DOCUMENTS', 'CONTENT_RU');
            $EN_IBLOCK_ID = \Only\Site\Helpers\IBlock::getIblockID('DOCUMENTS', 'CONTENT_EN');
            if ($arFields['IBLOCK_ID'] == $RU_IBLOCK_ID || $arFields['IBLOCK_ID'] == $EN_IBLOCK_ID) {
                \CModule::IncludeModule('iblock');
                $arFiles = [];
                foreach ($arFields['PROPERTY_VALUES'] as $id => &$arValues) {
                    $arProp = \CIBlockProperty::GetByID($id, $arFields['IBLOCK_ID'])->Fetch();
                    if ($arProp['PROPERTY_TYPE'] == 'F' && $arProp['CODE'] == 'FILE') {
                        $key_index = 0;
                        while (isset($arValues['n' . $key_index])) {
                            $arFiles[] = $arValues['n' . $key_index++];
                        }
                    } elseif ($arProp['PROPERTY_TYPE'] == 'L' && $arProp['CODE'] == 'OTHER_LANG' && $arValues[0]['VALUE']) {
                        $arValues[0]['VALUE'] = null;
                        if (!empty($arFiles)) {
                            $OTHER_IBLOCK_ID = $RU_IBLOCK_ID == $arFields['IBLOCK_ID'] ? $EN_IBLOCK_ID : $RU_IBLOCK_ID;
                            $arOtherElement = \CIBlockElement::GetList([],
                                [
                                    'IBLOCK_ID' => $OTHER_IBLOCK_ID,
                                    'CODE' => $arFields['CODE']
                                ], false, false, ['ID'])
                                ->Fetch();
                            if ($arOtherElement) {
                                /** @noinspection PhpDynamicAsStaticMethodCallInspection */
                                \CIBlockElement::SetPropertyValues($arOtherElement['ID'], $OTHER_IBLOCK_ID, $arFiles, 'FILE');
                            }
                        }
                    } elseif ($arProp['PROPERTY_TYPE'] == 'E') {
                        $elementIds = [];
                        foreach ($arValues as &$arValue) {
                            if ($arValue['VALUE']) {
                                $elementIds[] = $arValue['VALUE'];
                                $arValue['VALUE'] = null;
                            }
                        }
                        if (!empty($arFiles && !empty($elementIds))) {
                            $rsElement = \CIBlockElement::GetList([],
                                [
                                    'IBLOCK_ID' => \Only\Site\Helpers\IBlock::getIblockID('PRODUCTS', 'CATALOG_' . $RU_IBLOCK_ID == $arFields['IBLOCK_ID'] ? '_RU' : '_EN'),
                                    'ID' => $elementIds
                                ], false, false, ['ID', 'IBLOCK_ID', 'NAME']);
                            while ($arElement = $rsElement->Fetch()) {
                                /** @noinspection PhpDynamicAsStaticMethodCallInspection */
                                \CIBlockElement::SetPropertyValues($arElement['ID'], $arElement['IBLOCK_ID'], $arFiles, 'FILE');
                            }
                        }
                    }
                }
            }
        }
    }

}
