<?php
  
namespace MyOwn;

class CrmCustomTabManager
{
    protected \CCrmPerms $userPermissions;

    public function __construct()
    {
        $this->userPermissions = \CCrmPerms::GetCurrentUserPermissions();
    }

    public function getActualEntityTab(int $elementId, int $entityTypeID, array $tabs = [], string $guid): array
    {
        if ($entityTypeID > 31 && str_starts_with($guid, 'DYNAMIC_')) {
            $tabs = $this->getActualSmartTabs($tabs, $elementId, $entityTypeID);
        } else {
            switch ($entityTypeID) {
                case \CCrmOwnerType::Deal:
                    $tabs = $this->getActualDealTabs($tabs, $elementId);
                    break;
            }
        }

        return $tabs;
    }

    private function getActualDealTabs(array $tabs, int $elementId): array
    {
        $canUpdateDeal = \CCrmDeal::CheckUpdatePermission($elementId, $this->userPermissions);

        if ($canUpdateDeal) {
            $tabs[] = [
                'id' => 'component_companies_deal',
                'name' => 'Компании (компонент)',
                'enabled' => !empty($elementId),
                'loader' => [
                    'serviceUrl' => '/local/components/MyOwn/base.grid.deal/lazyload.ajax.php?&site=' . \SITE_ID . '&' . \bitrix_sessid_get(),
                    'componentData' => [
                        'template' => '',
                        'params' => [
                            'elementId' => $elementId,
                        ]
                    ]
                ]
            ];
        }

        return $tabs;
    }

    private function getActualSmartTabs(array $tabs, int $elementId, int $entityTypeID): array
    {
            $tabs[] = [
                'id' => 'component_companies_smart',
                'name' => 'Компании (компонент)',
                'enabled' => !empty($elementId),
                'loader' => [
                    'serviceUrl' => '/local/components/MyOwn/base.grid.smart/lazyload.ajax.php?&site=' . \SITE_ID . '&' . \bitrix_sessid_get(),
                    'componentData' => [
                        'template' => '',
                        'params' => [
                            'elementId' => $elementId,
                            'entityTypeId' => $entityTypeID,
                        ]
                    ]
                ]
            ];

        return $tabs;
    }
}