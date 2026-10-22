<?php
// my_shop/app/core/system_pulse.php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in'])) { exit(json_encode(['error' => 'unauthorized'])); }

$chat_file = __DIR__ . '/../../storage/logs/chat_history.json';
$tokens_dir = __DIR__ . '/../../../../auth_server/app/data/tokens/'; // Идем во второй карман Харона

// --- ПОЛУЧАЕМ СПИСОК "В СИСТЕМЕ СИДЯТ" ---
$online_users = [];
if (is_dir($tokens_dir)) {
    $token_files = glob($tokens_dir . 'gold_token_*.json');
    foreach ($token_files as $file) {
        $token_data = json_decode(file_get_contents($file), true);
        // Проверяем, что токен живой и не протух
        if (isset($token_data['expires_at']) && $token_data['expires_at'] > time()) {
            $online_users[] = [
                'name' => $token_data['user']['name'],
                'role' => $token_data['user']['role']
            ];
        }
    }
}

// --- ОБРАБОТКА ПОСТУПЛЕНИЯ НОВОГО СООБЩЕНИЯ ---
if (isset($_GET['action']) && $_GET['action'] === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['message_text'] ?? '');
    if ($text !== '') {
        $messages = file_exists($chat_file) ? json_decode(file_get_contents($chat_file), true) : [];
        $messages[] = [
            'time'   => date('H:i:s'),
            'author' => $_SESSION['user_data']['name'],
            'text'   => htmlspecialchars($text)
        ];
        // Держим в истории только последние 30 сообщений, чтобы не раздувать память
        if (count($messages) > 30) { array_shift($messages); }
        file_put_contents($chat_file, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    exit(json_encode(['success' => true]));
}

// --- СТАНДАРТНЫЙ ОТВЕТ: ОТДАЕМ ДАННЫЕ В JS ---
$chat_messages = file_exists($chat_file) ? json_decode(file_get_contents($chat_file), true) : [];

header('Content-Type: application/json');
echo json_encode([
    'online'   => $online_users,
    'messages' => $chat_messages
], JSON_UNESCAPED_UNICODE);
exit();
