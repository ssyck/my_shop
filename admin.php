<?php
// my_shop/admin.php
session_start();

// Подключаем конфигурацию ядра
$config = require_once __DIR__ . '/app/config/config.php';

// 1. Проверяем: авторизован ли уже администратор?
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    
    // 2. Ловим деревянный жетон (code) из Стикса
    if (isset($_GET['code'])) {
        // Жетон пойман! Запоминаем его временный статус
        $_SESSION['temporary_code'] = $_GET['code'];
        
        // ВАЖНО: На следующем шаге здесь будет подводный cURL запрос на $config['token_url']
        // для обмена $_SESSION['temporary_code'] на настоящее золото.
        
        // Пока сымитируем, что жетон сработал, чтобы не ломать текущий редирект
        $_SESSION['admin_logged_in'] = true;
        
        header("Location: " . $config['redirect_uri']);
        exit;
    }
    
    // 3. Жетона нет. Отправляем за ним на сервер авторизации, используя конфиг
    $query = http_build_query([
        'client_id'     => $config['client_id'],
        'redirect_uri'  => $config['redirect_uri'],
        'response_type' => 'code'
    ]);
    
    header("Location: " . $config['auth_url'] . "?" . $query);
    exit;
}

// 4. Доступ открыт
echo "status: admin_access_granted\n";
echo "config_used: client_id_is_" . $config['client_id'];
