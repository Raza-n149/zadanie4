<?php
/**
 * Задание 4: Валидация формы с использованием Cookies
 * Учебный сервер КубГУ
 * Выполнил: [Ваше имя]
 * Группа: [Ваша группа]
 */

// Устанавливаем кодировку UTF-8
header('Content-Type: text/html; charset=UTF-8');

// Определяем поля формы
$fields = [
    'fio' => [
        'required' => true,
        'label' => 'ФИО',
        'pattern' => '/^[а-яА-Яa-zA-Z\s\-]+$/u',
        'allowed' => 'буквы, пробелы и дефисы',
        'placeholder' => 'Иванов Иван Иванович'
    ],
    'email' => [
        'required' => true,
        'label' => 'Email',
        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'allowed' => 'латинские буквы, цифры, @, точка, дефис',
        'placeholder' => 'user@example.com'
    ],
    'phone' => [
        'required' => true,
        'label' => 'Телефон',
        'pattern' => '/^[\d\+\-\(\)\s]{10,20}$/',
        'allowed' => 'цифры, +, -, (, ), пробелы',
        'placeholder' => '+7 (123) 456-78-90'
    ],
    'birthdate' => [
        'required' => true,
        'label' => 'Дата рождения',
        'pattern' => '/^\d{4}-\d{2}-\d{2}$/',
        'allowed' => 'формат ГГГГ-ММ-ДД',
        'placeholder' => '1990-01-01',
        'type' => 'date'
    ],
    'gender' => [
        'required' => true,
        'label' => 'Пол',
        'pattern' => '/^(male|female)$/',
        'allowed' => 'male или female',
        'type' => 'radio'
    ]
];

// ============================================
// ОБРАБОТКА GET-ЗАПРОСА
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    $messages = array();
    $errors = array();
    $values = array();
    
    // Проверяем успешное сохранение
    if (isset($_COOKIE['save_success']) && $_COOKIE['save_success'] == '1') {
        // Удаляем куку после отображения
        setcookie('save_success', '', time() - 3600, '/');
        $messages[] = '<div class="message success">✅ Форма успешно отправлена! Данные сохранены на год.</div>';
    }
    
    // Загружаем ошибки из cookies
    foreach ($fields as $name => $config) {
        $errors[$name] = isset($_COOKIE[$name . '_error']) && $_COOKIE[$name . '_error'] == '1';
        
        if ($errors[$name]) {
            // Удаляем куку с ошибкой после чтения
            setcookie($name . '_error', '', time() - 3600, '/');
            
            // Добавляем сообщение об ошибке
            $required_text = $config['required'] ? 'обязательное поле' : 'поле';
            $messages[] = '<div class="message error">❌ Ошибка в поле "' . $config['label'] . '": ' 
                        . $required_text . '. Допустимо: ' . $config['allowed'] . '.</div>';
        }
    }
    
    // Загружаем значения полей из cookies
    foreach ($fields as $name => $config) {
        $values[$name] = isset($_COOKIE[$name . '_value']) ? $_COOKIE[$name . '_value'] : '';
    }
    
    // Подключаем форму
    include 'form.php';
    
// ============================================
// ОБРАБОТКА POST-ЗАПРОСА
// ============================================
} else {
    
    $has_errors = false;
    
    // Валидация полей
    foreach ($fields as $name => $config) {
        $value = isset($_POST[$name]) ? trim($_POST[$name]) : '';
        
        // Проверка обязательности
        if ($config['required'] && empty($value)) {
            setcookie($name . '_error', '1', 0, '/'); // 0 = до конца сессии браузера
            $has_errors = true;
        }
        // Проверка формата
        elseif (!empty($value) && !preg_match($config['pattern'], $value)) {
            setcookie($name . '_error', '1', 0, '/');
            $has_errors = true;
        }
        
        // Сохраняем значение (на 30 дней)
        setcookie($name . '_value', $value, time() + 30 * 24 * 60 * 60, '/');
    }
    
    // Если есть ошибки - перезагружаем страницу методом GET
    if ($has_errors) {
        header('Location: index.php');
        exit();
    }
    
    // ========================================
    // УСПЕШНОЕ СОХРАНЕНИЕ
    // ========================================
    
    // Удаляем все куки с ошибками
    foreach ($fields as $name => $config) {
        setcookie($name . '_error', '', time() - 3600, '/');
    }
    
    // Сохраняем значения НА ГОД (365 дней)
    foreach ($fields as $name => $config) {
        if (isset($_POST[$name]) && !empty($_POST[$name])) {
            setcookie($name . '_value', $_POST[$name], time() + 365 * 24 * 60 * 60, '/');
        }
    }
    
    // Устанавливаем куку успешного сохранения
    setcookie('save_success', '1', time() + 365 * 24 * 60 * 60, '/');
    
    // Перенаправляем на GET
    header('Location: index.php');
    exit();
}
?>