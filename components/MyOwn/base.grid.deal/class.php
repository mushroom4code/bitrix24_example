<?php

namespace MyOwn\Components;
use \Bitrix\Crm\ItemIdentifier;
use \Bitrix\Crm\Service\Container;

use Bitrix\Main\Grid;

\Bitrix\Main\Loader::includeModule('crm');

class BaseGridComponent extends \CBitrixComponent
{
    const GRID_ID = 'BASE_GRID';
    const ENUM_UF_ID = 'UF_CRM_1727869498897';
    const AR_CUSTOM_FIELDS = [
        'UF_CRM_1728662634' => 'Сайт/Соц.сеть/Продающий канал',
        self::ENUM_UF_ID => 'Платформа размещения курса',
        'UF_CRM_1728662675' => 'Формат продаж',
        'UF_CRM_1727869538570' => 'Дополнительная информация о партнёре',
        'UF_CRM_1728662723' => 'Статус компании',
        'UF_CRM_1727869758793' => 'Дата первой авторизации',
        'UF_CRM_1727869787258' => 'Дата создания последней сделки',
        'UF_CRM_1728662737' => 'Платформа, на которой размещен курс',
        'UF_CRM_1727869572705' => 'Название обучения, коучинга или курса',
        'UF_CRM_1728662760' => 'Ответственный менеджер',
    ];

    public function executeComponent()
    {
        $grid_id = self::GRID_ID;
        $arCustomFields = self::AR_CUSTOM_FIELDS;

        $grid_options = new Grid\Options($grid_id);
        $sort = $this->getSorting($grid_options);

        $item = new ItemIdentifier(\CCrmOwnerType::Deal, $this->arParams['elementId']);
        $children = Container::getInstance()->getRelationManager()->getChildElements($item);
        $parents = Container::getInstance()->getRelationManager()->getParentElements($item);
        $result = array_merge($children, $parents);

        $arCompanies = [];
        foreach ($result as $element) {
            if ($element->getEntityTypeId() === \CCrmOwnerType::Company) {
                $arCompanies[] = $element->getEntityId();
            }
        }

        $grid_rows = [];

        if (!empty($arCompanies)) {
            $entityResult = \CCrmCompany::GetListEx(
                $sort,
                [
                    'ID' => $arCompanies
                ],
                false,
                false,
                array_keys($arCustomFields)
            );

            while( $entity = $entityResult->fetch() )
            {
                $prepared_element = $this->getPreparedElement($entity);

                $row = [
                    'id' => $entity['ID'],
                    'data' => $entity,
                    'columns' => $prepared_element,
                    'editable' => 'Y',
                    'actions' => []
                ];

                $grid_rows[] = $row;
            }
        }

        $this->arResult['GRID_ID'] = $grid_id;
        $this->arResult['GRID_COLUMNS'] = $this->getGridColumns();
        $this->arResult['ROWS'] = $grid_rows;

        $this->includeComponentTemplate();
    }

    public function getSorting($grid)
    {
        $sort = $grid->GetSorting([
            'sort' => [
                'ID' => 'DESC'
            ],
            'vars' => [
                'by' => 'by',
                'order' => 'order'
            ]
        ]);

        return $sort['sort'];
    }

    public function getPreparedElement($fields)
    {
        if (!empty($fields[self::ENUM_UF_ID])) {
            $res = \CUserFieldEnum::GetList(array(), array("ID" => $fields[self::ENUM_UF_ID]));
            $row = $res->Fetch();
            $fields[self::ENUM_UF_ID] = $row['VALUE'];
        }
        return $fields;
    }

    private function getGridColumns(): array
    {
        $columns = [];
        foreach(self::AR_CUSTOM_FIELDS as $fieldKey => $fieldName) {
            $columns[] = [
                'id' => $fieldKey,
                'name' => $fieldName,
                'sort' => $fieldKey,
                'default' => true
            ];
        }

        return $columns;
    }
}