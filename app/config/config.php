<?php
// my_shop/app/config/config.php
declare(strict_types=1);

return [
    // Параметры самого магазина (клиента) для предъявления Харону
    'client_id'     => 'my_shop_admin',
    'client_secret' => 'gold_coin_secret_999_xyz', 
    
    // Локальные URL-координаты в XAMPP
    'shop_base_url' => 'http://my_shop.local',
    'redirect_uri'  => 'http://my_shop.local/admin.php',
    
    // Куда отправлять пользователя за деревянным жетоном (Code)
    'auth_url'      => 'http://auth_server.local/authorize_core.php',
    
    // Скрытый подводный шлюз Харона для обмена жетонов на золото (Token)
    'token_url'     => 'http://auth_server.local/token_exchange.php',

    // Жесткие локальные пути к стилям станка
    'assets' => [
        'bootstrap_css' => '/public/assets/css/bootstrap5/bootstrap.min.css',
        'bootstrap_js'  => '/public/assets/js/bootstrap5/bootstrap.bundle.min.js',
        'custom_css'    => '/public/assets/css/admin_dashboard.css'
    ]
];
