<?php $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
    'GRID_ID' => $arResult['GRID_ID'],
    'COLUMNS' => $arResult['GRID_COLUMNS'],
    'ROWS' => $arResult['ROWS'],
    'AJAX_MODE' => 'Y',
    'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
    'AJAX_OPTION_JUMP' => 'N',
    'SHOW_ROW_CHECKBOXES' => false,
    'SHOW_CHECK_ALL_CHECKBOXES' => false,
    'SHOW_ROW_ACTIONS_MENU' => true,
    'SHOW_GRID_SETTINGS_MENU' => false,
    'SHOW_NAVIGATION_PANEL' => false,
    'SHOW_PAGINATION' => false,
    'SHOW_SELECTED_COUNTER' => false,
    'SHOW_TOTAL_COUNTER' => false,
    'SHOW_PAGESIZE' => false,
    'SHOW_ACTION_PANEL' => false,
    'ALLOW_COLUMNS_SORT' => true,
    'ALLOW_COLUMNS_RESIZE' => true,
    'ALLOW_HORIZONTAL_SCROLL' => true,
    'ALLOW_SORT' => true,
    'ALLOW_PIN_HEADER' => true,
    'AJAX_OPTION_HISTORY' => 'N',
    "ENABLE_COLLAPSIBLE_ROWS" => true
], $component); ?>

<?php if (!empty($arParams['AJAX_LOADER'])) { ?>
    <script>
        BX.addCustomEvent('Grid::beforeRequest', function (gridData, argse) {
            if (argse.gridId != '<?=$arResult['GRID_ID'];?>') {
                return;
            }
            
            argse.method = 'POST'
            argse.data = <?= \Bitrix\Main\Web\Json::encode($arParams['AJAX_LOADER']['data']) ?>
        });
    </script>
<?php } ?>