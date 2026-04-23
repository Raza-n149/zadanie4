<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 4 - Валидация формы</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 550px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4CAF50;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .required {
            color: red;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        /* Стиль для полей с ошибками - красная рамка */
        .error-field {
            border: 2px solid red !important;
            background-color: #ffe6e6 !important;
        }
        
        /* Сообщения */
        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Радио кнопки */
        .radio-group {
            display: flex;
            gap: 20px;
            padding: 8px 0;
        }
        
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: normal;
            cursor: pointer;
        }
        
        .radio-group input {
            width: auto;
            margin: 0;
        }
        
        /* Кнопка отправки */
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        .help-text {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eee;
        }
        
        .info {
            background: #e7f3ff;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #0066cc;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Задание 4</h1>
        <div class="subtitle">Проверка заполнения полей формы с использованием Cookies</div>
        
        <!-- Вывод сообщений -->
        <?php if (!empty($messages)): ?>
            <div id="messages">
                <?php foreach ($messages as $msg): ?>
                    <?php echo $msg; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Форма -->
        <form action="" method="POST">
            <!-- Поле ФИО -->
            <div class="form-group">
                <label>ФИО <span class="required">*</span></label>
                <input type="text" 
                       name="fio" 
                       value="<?php echo htmlspecialchars($values['fio']); ?>"
                       <?php echo !empty($errors['fio']) ? 'class="error-field"' : ''; ?>
                       placeholder="Иванов Иван Иванович">
                <div class="help-text">Допустимы: буквы, пробелы, дефисы</div>
            </div>
            
            <!-- Поле Email -->
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" 
                       name="email" 
                       value="<?php echo htmlspecialchars($values['email']); ?>"
                       <?php echo !empty($errors['email']) ? 'class="error-field"' : ''; ?>
                       placeholder="user@example.com">
                <div class="help-text">Формат: name@domain.com</div>
            </div>
            
            <!-- Поле Телефон -->
            <div class="form-group">
                <label>Телефон <span class="required">*</span></label>
                <input type="tel" 
                       name="phone" 
                       value="<?php echo htmlspecialchars($values['phone']); ?>"
                       <?php echo !empty($errors['phone']) ? 'class="error-field"' : ''; ?>
                       placeholder="+7 (123) 456-78-90">
                <div class="help-text">Допустимы: цифры, +, -, (, ), пробелы</div>
            </div>
            
            <!-- Поле Дата рождения -->
            <div class="form-group">
                <label>Дата рождения <span class="required">*</span></label>
                <input type="date" 
                       name="birthdate" 
                       value="<?php echo htmlspecialchars($values['birthdate']); ?>"
                       <?php echo !empty($errors['birthdate']) ? 'class="error-field"' : ''; ?>>
                <div class="help-text">Формат: ГГГГ-ММ-ДД</div>
            </div>
            
            <!-- Поле Пол -->
            <div class="form-group">
                <label>Пол <span class="required">*</span></label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="gender" value="male" 
                               <?php echo ($values['gender'] == 'male') ? 'checked' : ''; ?>
                               <?php echo !empty($errors['gender']) ? 'class="error-field"' : ''; ?>>
                        Мужской
                    </label>
                    <label>
                        <input type="radio" name="gender" value="female" 
                               <?php echo ($values['gender'] == 'female') ? 'checked' : ''; ?>
                               <?php echo !empty($errors['gender']) ? 'class="error-field"' : ''; ?>>
                        Женский
                    </label>
                </div>
            </div>
            
            <button type="submit">Отправить форму</button>
        </form>
        
        <hr>
        <div class="info">
            🔄 <strong>Cookies:</strong> При успешной отправке данные сохраняются на 1 год.<br>
            ⚠️ <strong>При ошибках:</strong> Поля подсвечиваются красным, сообщения выводятся сверху.
        </div>
    </div>
</body>
</html>