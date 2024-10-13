<?php

$eventManager = \Bitrix\Main\EventManager::getInstance();


$eventManager->addEventHandler('crm', 'onEntityDetailsTabsInitialized', [
        'MyOwn\\Handler',
        'setCustomTabs'
    ]
);