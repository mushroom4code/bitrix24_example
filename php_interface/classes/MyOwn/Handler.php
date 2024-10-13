<?php

namespace MyOwn;

use Bitrix\Main\EventResult;
use Bitrix\Main\Event;

class Handler
{
    static function setCustomTabs(Event $event): EventResult
    {
        $entityId = $event->getParameter('entityID');
        $entityTypeID = $event->getParameter('entityTypeID');
        $guid = $event->getParameter('guid');
        $tabs = $event->getParameter('tabs');

        $crmCustomTabManager = new CrmCustomTabManager();

        $tabs = $crmCustomTabManager->getActualEntityTab($entityId, $entityTypeID, $tabs, $guid);

        return new EventResult(EventResult::SUCCESS, [
            'tabs' => $tabs,
        ]);
    }
}