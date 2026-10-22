<?php
// my_shop/app/views/layouts/dashboard_layout.php
declare(strict_types=1);

// Защита от прямого вызова файла в обход admin.php
if (!isset($_SESSION['admin_logged_in'])) { exit(); }

$user_data = $_SESSION['user_data'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления — MyNewAttemp Engine</title>
    
    <!-- Жесткий локальный автономный запуск стилей -->
    <link rel="stylesheet" href="<?php echo $config['assets']['bootstrap_css']; ?>">
    <link rel="stylesheet" href="<?php echo $config['assets']['custom_css']; ?>">
</head>

<body class="bg-light">

    <!-- Шапка Дашборда -->
    <nav class="navbar navbar-dark bg-dark shadow-sm py-2">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1" style="font-family: monospace;">Система ЧПУ Магазина</span>
            <div class="d-flex align-items-center">
                <div class="navbar-text text-white-50 me-3">
                    Вы вошли как: <strong class="text-white"><?php echo htmlspecialchars($user_data['name']); ?></strong> 
                    (<small class="text-warning text-uppercase" style="font-size: 10px;"><?php echo htmlspecialchars($user_data['role']); ?></small>)
                </div>
                <a href="?action=logout" class="text-decoration-none small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 11px; color: #dc3545;">
                    [ ВЫХОД ]
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            
            <!-- Левое меню-аккордеон (Слепой автомат по плоским купонам) -->
            <aside class="col-md-3 col-lg-2 mb-4">
                <div class="accordion shadow-sm" id="menu_accordion">
                    <?php 
                    foreach (SYSTEM_MENUS as $mod_key => $menu_info) {
                        if (!$is_god_mode && !in_array($menu_info['flag'], $my_flags, true)) {
                            continue;
                        }

                        $is_current_module = ($current_mod === $mod_key);
                        $collapse_state_class = $is_current_module ? 'show' : '';
                        $button_collapsed_class = $is_current_module ? '' : 'collapsed';
                        ?>
                        <div class="accordion-item border-bottom-0">
                            <h2 class="accordion-header" id="heading_<?php echo $mod_key; ?>">
                                <button class="accordion-button py-2 px-3 bg-light text-dark font-weight-bold small text-uppercase <?php echo $button_collapsed_class; ?>" 
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $mod_key; ?>" 
                                        style="letter-spacing: 0.5px; font-size: 11px;">
                                    <?php echo htmlspecialchars($menu_info['title'] ?? 'Без названия'); ?>
                                </button>
                            </h2>
                            <div id="collapse_<?php echo $mod_key; ?>" class="accordion-collapse collapse <?php echo $collapse_state_class; ?>" data-bs-parent="#menu_accordion">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush">
                                        <?php 
                                        foreach ($menu_info['submenu'] as $op_key => $sub_item) {
                                            $action_name = $sub_item['action'] ?? $op_key;
                                            $is_active = ($is_current_module && $current_op === $action_name) ? 'active' : '';
                                            echo '<a href="?mod=' . $mod_key . '&op=' . $action_name . '" class="list-group-item list-group-item-action ps-4 py-2 small ' . $is_active . '" style="font-size: 13px; border: none;">';
                                            echo '— ' . htmlspecialchars($sub_item['title'] ?? 'Операция');
                                            echo '</a>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </aside>

            <!-- Центральная Рабочая область -->
            <main class="col-md-9 col-lg-10">
                <div class="card shadow-sm p-4 rounded-0 border">
                    <?php 
                    if ($current_mod !== '' && $current_op !== '') {
                        $view_path = __DIR__ . "/../{$current_mod}/{$current_op}_form.php"; // Скорректировали относительный путь из папки layouts
                        if (file_exists($view_path)) {
                            require_once $view_path;
                        } else {
                            echo "<div class='alert alert-danger small rounded-0'>Ошибка: Файл отображения для [{$current_mod} ➔ {$current_op}] не найден.</div>";
                        }
                    } else {
                        echo '<h5 class="text-secondary fw-bold text-uppercase" style="font-family: monospace;">Рабочая область модуля</h5><hr class="mt-1">';
                        echo '<p class="text-muted small">Выберите доступный раздел в меню слева для начала работы.</p>';
                    }
                    ?>
                </div>
            </main>

        </div>
    </div>

    <!-- Жесткий локальный скрипт -->
    <script src="<?php echo $config['assets']['bootstrap_js']; ?>"></script>
    
</body>
</body>
</html>
