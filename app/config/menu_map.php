<?php
// my_shop/app/config/menu_map.php
declare(strict_types=1);

define('SYSTEM_MENUS', [
    'ad' => [
        'title'   => 'Система и моды',
        'flag'    => 'ul', 
        'submenu' => [
            'staff_manager' => ['title' => 'Управление персоналом', 'action' => 'staff_manager'],
            'plugin_router' => ['title' => 'Менеджер модификаций', 'action' => 'plugin_router']
        ]
    ],
    'catalog' => [
        'title'   => 'Каталог товаров',
        'flag'    => 'pt', 
        'submenu' => [
            'product_list' => ['title' => 'Листинг номенклатуры', 'action' => 'product_list']
        ]
    ],
    'orders' => [
        'title'   => 'Учет заказов',
        'flag'    => 'od', 
        'submenu' => [
            'daily_queue' => ['title' => 'Очередь за день', 'action' => 'daily_queue']
        ]
    ]
]);
