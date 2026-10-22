<?php
// my_shop/app/config/config.php

return [
    // Параметры самого магазина (клиента)
    'client_id'     => 'my_shop_admin',
    'client_secret' => 'gold_coin_secret_999_xyz', // Наш тайный пароль для сервера авторизации
    
    // Куда перенаправлять пользователя для получения деревянного жетона (Code)
    'auth_url'      => 'http://auth_server.local/authorize_core.php',
    
    // Секретный подводный шлюз сервера авторизации для обмена жетона на золото (Token)
    'token_url'     => 'http://auth_server.local/token_exchange.php',
    
    // Точка возврата (наш собственный проводник)
    'redirect_uri'  => 'http://my_shop.local/admin.php',
];
