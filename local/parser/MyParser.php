<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

class MyParser
{
    private $iblockId;
    private $props;
    //Поле USER требуется для проверки на админа
    private $USER;

    public function __construct(int $iblockId, $USER)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $this->iblockId = $iblockId;
        $this->props = [];
        $this->USER = $USER;
        if (!$USER->IsAdmin()) {
            LocalRedirect('/');
        }
    }

    //Метод для считывания данных из CSV
    public function importVacanciesFromCSV(string $filename)
    {
        $this->prepareProperties();
        if (($handle = fopen($filename, "r")) !== false) {
            $row = 1;
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($row == 1) {
                    $row++;
                    continue;
                }
                $row++;
                $this->createVacancyElement($data);
            }
            fclose($handle);
        }
    }

    private function prepareProperties()
    {
        $rsElement = CIBlockElement::getList([], ['IBLOCK_ID' => $this->iblockId],
            false, false, ['ID', 'NAME']);
        while ($ob = $rsElement->GetNextElement()) {
            $arFields = $ob->GetFields();
            $key = str_replace(['»', '«', '(', ')'], '', $arFields['NAME']);
            $key = strtolower($key);
            $arKey = explode(' ', $key);
            $key = '';
            foreach ($arKey as $part) {
                if (strlen($part) > 2) {
                    $key .= trim($part) . ' ';
                }
            }
            $key = trim($key);
            $this->props['OFFICE'][$key] = $arFields['ID'];
        }

        $rsProp = CIBlockPropertyEnum::GetList(
            ["SORT" => "ASC", "VALUE" => "ASC"],
            ['IBLOCK_ID' => $this->iblockId]
        );
        while ($arProp = $rsProp->Fetch()) {
            $key = trim($arProp['VALUE']);
            $this->props[$arProp['PROPERTY_CODE']][$key] = $arProp['ID'];
        }

        $rsElements = CIBlockElement::GetList([], ['IBLOCK_ID' => $this->iblockId], false, false, ['ID']);
        while ($element = $rsElements->GetNext()) {
            CIBlockElement::Delete($element['ID']);
        }
    }

//Добавление элементов
    private function createVacancyElement($data)
    {
        $el = new CIBlockElement;
        $PROP = $this->prepareVacancyProperties($data);
        $arLoadProductArray = [
            "MODIFIED_BY" => $this->USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => $this->iblockId,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $data[3],
            "ACTIVE" => end($data) ? 'Y' : 'N',
        ];

        $this->outPut($el, $arLoadProductArray);
    }

    //Работа со данными
    private function prepareVacancyProperties($data)
    {
        $PROP = [];
        $PROP['ACTIVITY'] = $data[9];
        $PROP['FIELD'] = $data[11];
        $PROP['OFFICE'] = $data[1];
        $PROP['LOCATION'] = $data[2];
        $PROP['REQUIRE'] = $data[4];
        $PROP['DUTY'] = $data[5];
        $PROP['CONDITIONS'] = $data[6];
        $PROP['EMAIL'] = $data[12];
        $PROP['DATE'] = date('d.m.Y');
        $PROP['TYPE'] = $data[8];
        $PROP['SALARY_TYPE'] = '';
        $PROP['SALARY_VALUE'] = $data[7];
        $PROP['SCHEDULE'] = $data[10];

        foreach ($PROP as $key => &$value) {
            $value = trim($value);
            $value = str_replace('\n', '', $value);
            if (stripos($value, '•') !== false) {
                $value = explode('•', $value);
                array_splice($value, 0, 1);
                foreach ($value as &$str) {
                    $str = trim($str);
                }
            } elseif ($this->props[$key]) {
                $arSimilar = [];
                foreach ($this->props[$key] as $propKey => $propVal) {
                    if ($key == 'OFFICE') {
                        $value = strtolower($value);
                        if ($value == 'центральный офис') {
                            $value .= 'свеза ' . $data[2];
                        } elseif ($value == 'лесозаготовка') {
                            $value = 'свеза ресурс ' . $value;
                        } elseif ($value == 'свеза тюмень') {
                            $value = 'свеза тюмени';
                        }
                        $arSimilar[similar_text($value, $propKey)] = $propVal;
                    }
                    if (stripos($propKey, $value) !== false) {
                        $value = $propVal;
                        break;
                    }

                    if (similar_text($propKey, $value) > 50) {
                        $value = $propVal;
                    }
                }
                if ($key == 'OFFICE' && !is_numeric($value)) {
                    ksort($arSimilar);
                    $value = array_pop($arSimilar);
                }
            }
        }

        if ($PROP['SALARY_VALUE'] == '-') {
            $PROP['SALARY_VALUE'] = '';
        } elseif ($PROP['SALARY_VALUE'] == 'по договоренности') {
            $PROP['SALARY_VALUE'] = '';
            $PROP['SALARY_TYPE'] = $this->props['SALARY_TYPE']['договорная'];
        } else {
            $arSalary = explode(' ', $PROP['SALARY_VALUE']);
            if ($arSalary[0] == 'от' || $arSalary[0] == 'до') {
                $PROP['SALARY_TYPE'] = $this->props['SALARY_TYPE'][$arSalary[0]];
                array_splice($arSalary, 0, 1);
                $PROP['SALARY_VALUE'] = implode(' ', $arSalary);
            } else {
                $PROP['SALARY_TYPE'] = $this->props['SALARY_TYPE']['='];
            }
        }

        return $PROP;
    }

//Вывод списка добавленных элементов с ID
    public function outPut(CIBlockElement $el, array $arLoadProductArray): void
    {
        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
        } else {
            echo "Error: " . $el->LAST_ERROR . '<br>';
        }
    }
}

$iblockId = 4; // ID вашего инфоблока, куда нужно импортировать вакансии
$parser = new MyParser($iblockId, $USER);
$parser->importVacanciesFromCSV("vacancy.csv");

