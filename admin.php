<?php
// my_shop/admin.php
declare(strict_types=1);

// Включаем прожектор отладки на этапе первого пуска
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🛑 ВРЕМЕННАЯ ИНЪЕКЦИЯ ДЛЯ ТЕСТА ИНТЕРФЕЙСА (Удалим, когда свяжем с Хароном)
$_SESSION['admin_logged_in'] = true;
$_SESSION['user_data'] = [
    'uuid'     => 'usr_6717bd94_a3f01b2c',
    'username' => 'admin',
    'name'     => 'Хозяин',
    'role'     => 'super_admin'
];
$_SESSION['user_flags'] = ['ac']; 
// 🛑 КОНЕЦ ИНЪЕКЦИИ

// НАДЖЕСТКИЙ ЗАПРЕТ КЭШИРОВАНИЯ ДЛЯ БРАУЗЕРА:
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache");                                   
header("Expires: 0");                                          

$config = require_once __DIR__ . '/app/config/config.php';

// --- КОНТУР БЕЗОПАСНОСТИ ХАРОНА (ВЫХОД) ---
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
    header("Location: " . $config['auth_url'] . '?action=logout&redirect_uri=' . urlencode($config['shop_base_url'] . '/index.php'));
    exit();
}

// Проверка авторизации
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    if (isset($_GET['code'])) {
        header("Location: " . $config['redirect_uri']);
        exit();
    }
    header("Location: " . $config['auth_url'] . "?" . http_build_query(['client_id' => $config['client_id'], 'redirect_uri' => $config['redirect_uri'], 'response_type' => 'code']));
    exit();
}

$user        = $_SESSION['user_data'];
$my_flags    = $_SESSION['user_flags'] ?? [];
$is_god_mode = in_array('ac', $my_flags, true);
$current_mod = $_GET['mod'] ?? '';
$current_op  = $_GET['op'] ?? '';

require_once __DIR__ . '/app/config/menu_map.php';

if ($current_mod !== '') {
    if (!$is_god_mode && !in_array(SYSTEM_MENUS[$current_mod]['flag'] ?? '', $my_flags, true)) {
        die("status: access_denied_by_charon_routing");
    }
}

// ШАГ 1 PRG: Перехват тяжелых POST-запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $current_mod !== '' && $current_op !== '') {
    $controller_path = __DIR__ . "/app/controllers/{$current_mod}/{$current_op}_controller.php";
    if (file_exists($controller_path)) {
        require_once $controller_path;
        exit();
    }
}

// ШАГ 2: Подключаем чистый изолированный шаблон интерфейса (GET)
require_once __DIR__ . '/app/views/layouts/dashboard_layout.php';
