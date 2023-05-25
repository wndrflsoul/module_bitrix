<?php

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\ElementTable;

class IblockHandlerAgents
{
    public static function clearOldLogs()
    {
        // Подключение iblock
        Loader::includeModule('iblock');

        // Ищем инфоблок с именем 'LOG'
        $iblock = IblockTable::getList([
            'filter' => ['NAME' => 'LOG'],
            'select' => ['ID']
        ])->fetch();

        if ($iblock) {
            // Список элементов связанных с найденным инфоблоком
            $elements = ElementTable::getList([
                'filter' => ['IBLOCK_ID' => $iblock['ID']],
                'order' => ['ID' => 'DESC'],
                'select' => ['ID']
            ])->fetchAll();

            $elementIds = array_column($elements, 'ID');
            
            // Если количество элементов больше 9, удаляем элементы начиная с 10-го
            if (count($elementIds) > 9) {
                $deleteIds = array_slice($elementIds, 9);

                foreach ($deleteIds as $elementId) {
                    // Удаляем элемент информационного блока
                    ElementTable::delete($elementId);
                }
            }
        }

        // Возвращаем строку в качестве результата выполнения метода
        return "clearOldLogs();";
    }
}