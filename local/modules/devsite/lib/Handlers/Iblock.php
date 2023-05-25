<?php

class IblockHandler
{
    // Метод для получения информационного блока "LOG"
    private function getLogIblock()
    {
        $iBlockRes = CIBlock::GetList(Array(), Array('NAME' => 'LOG'));
        $iBlock = $iBlockRes->Fetch();
        
        return $iBlock;
    }
    
    // Метод для получения раздела "LOG"
    private function getLogSection()
    {
        $iBlockSectionRes = CIBlockSection::GetList(Array(), Array('NAME' => 'LOG'));
        $iBlockSection = $iBlockSectionRes->Fetch();
        
        return $iBlockSection;
    }
    
    // Метод для создания раздела "LOG", если он не существует
    private function createLogSection($iBlock)
    {
        $CIBlockSection = new CIBlockSection;
        
        $CIBlockSectionFields = Array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $iBlock['ID'],
            "NAME" => 'LOG'
        );
        
        $CIBlockSectionGetListRes = CIBlockSection::GetList(Array(), Array('NAME' => 'LOG'));
        $CIBlockSectionGetList = $CIBlockSectionGetListRes->Fetch();
        
        if ($CIBlockSectionGetList["ID"] == 0) {
            $sectionId = $CIBlockSection->Add($CIBlockSectionFields);
        } else {
            $sectionId = $CIBlockSectionGetList["ID"];
        }
        
        return $sectionId;
    }
    
    // Метод для создания записи в "LOG"
    public function createLogEntry(&$arFields)
    {
        $iBlock = $this->getLogIblock();
        
        if ($iBlock['NAME'] != "LOG") {
            if ($arFields["ID"] > 0) {
                $iBlockSection = $this->getLogSection();
                
                if (!$iBlockSection) {
                    $sectionId = $this->createLogSection($iBlock);
                } else {
                    $sectionId = $iBlockSection["ID"];
                }
                
                $CIBlockElement = new CIBlockElement;
                
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => $sectionId,
                    "IBLOCK_ID" => $iBlock['ID'],
                    "NAME" => 'ID: ' . $arFields["ID"],
                    "ACTIVE" => "Y",
                    "PREVIEW_TEXT" => "Is it a log? " . $iBlock['NAME'] . " -> " . $arFields["NAME"] . "?",
                    "DETAIL_TEXT" => "Is it a log? " . $iBlock['NAME'] . " -> " . $arFields["NAME"] . "?",
                    "ACTIVE_FROM" => date("d.m.Y H:i:s")
                );
                
                $productId = $CIBlockElement->Add($arLoadProductArray);
                
                if ($productId) {
                    echo "New ID: " . $productId;
                } else {
                    echo "Error: " . $CIBlockElement->LAST_ERROR;
                }
            }
        }
    }
    
    // Метод для обновления записи в "LOG"
    public function updateLogEntry(&$arFields)
    {
        $iBlock = $this->getLogIblock();
        
        if ($iBlock['NAME'] != "LOG") {
            if ($arFields["ID"] > 0) {
                $iBlockSection = $this->getLogSection();
                
                if ($iBlockSection) {
                    $CIBlockElement = new CIBlockElement;
                    
                    $arLoadProductArray = Array(
                        "IBLOCK_SECTION_ID" => $iBlockSection["ID"],
                        "IBLOCK_ID" => $iBlock['ID'],
                        "NAME" => 'ID: ' . $arFields["ID"],
                        "ACTIVE" => "Y",
                        "PREVIEW_TEXT" => "Is it a log? " . $iBlock['NAME'] . " -> " . $arFields["NAME"] . "?",
                        "DETAIL_TEXT" => "Is it a log? " . $iBlock['NAME'] . " -> " . $arFields["NAME"] . "?",
                        "ACTIVE_FROM" => date("d.m.Y H:i:s")
                    );
                    
                    $res = $CIBlockElement->Update($iBlockSection["ID"], $arLoadProductArray);
                }
            }
        }
    }
}