<?php
session_start();
require_once 'connect.php';

$error = '';
$success = '';
$form_data = [];

// Jeśli użytkownik już zalogowany, przekieruj do logged.php
if (isset($_SESSION['user_id'])) {
    header("Location: logged.php");
    exit();
}

// Obsługa rejestracji
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_submit'])) {
    $login = trim($_POST['login']);
    $name = trim($_POST['name']);
    $last_name = trim($_POST['last_name']);
    $gender = $_POST['gender'] ?? '';
    $preference = $_POST['preference'] ?? '';
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Zapisz dane formularza do ponownego wypełnienia w przypadku błędu
    $form_data = [
        'login' => $login,
        'name' => $name,
        'last_name' => $last_name,
        'gender' => $gender,
        'preference' => $preference
    ];
    
    // Walidacja
    if (empty($login) || empty($name) || empty($last_name) || empty($gender) || empty($preference) || empty($password)) {
        $error = "Proszę wypełnić wszystkie pola.";
    } elseif ($password !== $password_confirm) {
        $error = "Hasła nie są identyczne.";
    } else {
        // Walidacja hasła wyrażeniami regularnymi
        $password_errors = [];
        
        if (strlen($password) < 8) {
            $password_errors[] = "minimum 8 znaków";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $password_errors[] = "przynajmniej jedna wielka litera";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $password_errors[] = "przynajmniej jedna mała litera";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $password_errors[] = "przynajmniej jedna cyfra";
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $password_errors[] = "przynajmniej jeden znak specjalny (!@#$%^&*(),.?\":{}|<>)";
        }
        
        if (!empty($password_errors)) {
            $error = "Hasło nie spełnia wymagań: " . implode(", ", $password_errors) . ".";
        } else {
            try {
                // Sprawdź czy login już istnieje
                $stmt = $conn->prepare("SELECT id FROM user WHERE login = ?");
                $stmt->execute([$login]);
                
                if ($stmt->fetch()) {
                    $error = "Login jest już zajęty. Wybierz inny.";
                } else {
                    // Generowanie salta i hashowanie hasła
                    $salt = base64_encode(random_bytes(16));
                    $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                    
                    // Wstawianie do bazy
                    $stmt = $conn->prepare("INSERT INTO user (login, name, last_name, gender, preference, salt, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$login, $name, $last_name, $gender, $preference, $salt, $password_hash]);
                    
                    $success = "Rejestracja przebiegła pomyślnie! Możesz się teraz zalogować.";
                    $form_data = []; // Wyczyść formularz po sukcesie
                    
                    // Przekierowanie po 2 sekundach
                    header("refresh:2;url=logowanie.php");
                }
            } catch (PDOException $e) {
                $error = "Błąd bazy danych: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .register-header h1 {
            color: #667eea;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .register-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .password-requirements {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .password-requirements h3 {
            color: #667eea;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .password-requirements ul {
            list-style: none;
            padding-left: 0;
        }
        
        .password-requirements li {
            color: #666;
            font-size: 13px;
            padding: 4px 0;
            padding-left: 20px;
            position: relative;
        }
        
        .password-requirements li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #4CAF50;
            font-weight: bold;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-bottom: 15px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #f8f9fa;
        }
        
        .error-message {
            background: #ff4444;
            color: white;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        
        .success-message {
            background: #4CAF50;
            color: white;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Rejestracja</h1>
            <p>Utwórz nowe konto</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="login">Login *</label>
                <input type="text" id="login" name="login" required 
                       value="<?= htmlspecialchars($form_data['login'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="name">Imię *</label>
                <input type="text" id="name" name="name" required 
                       value="<?= htmlspecialchars($form_data['name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="last_name">Nazwisko *</label>
                <input type="text" id="last_name" name="last_name" required 
                       value="<?= htmlspecialchars($form_data['last_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="gender">Płeć *</label>
                <select id="gender" name="gender" required>
                    <option value="">Wybierz...</option>
                    <option value="Mężczyzna" <?= ($form_data['gender'] ?? '') === 'Mężczyzna' ? 'selected' : '' ?>>Mężczyzna</option>
                    <option value="Kobieta" <?= ($form_data['gender'] ?? '') === 'Kobieta' ? 'selected' : '' ?>>Kobieta</option>
                    <option value="Inna" <?= ($form_data['gender'] ?? '') === 'Inna' ? 'selected' : '' ?>>Inna</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="preference">System operacyjny *</label>
                <select id="preference" name="preference" required>
                    <option value="">Wybierz...</option>
                    <option value="Windows" <?= ($form_data['preference'] ?? '') === 'Windows' ? 'selected' : '' ?>>Windows</option>
                    <option value="MacOS" <?= ($form_data['preference'] ?? '') === 'MacOS' ? 'selected' : '' ?>>MacOS</option>
                    <option value="Linux" <?= ($form_data['preference'] ?? '') === 'Linux' ? 'selected' : '' ?>>Linux</option>
                </select>
            </div>
            
            <div class="password-requirements">
                <h3>Wymagania hasła:</h3>
                <ul>
                    <li>Minimum 8 znaków</li>
                    <li>Przynajmniej jedna wielka litera (A-Z)</li>
                    <li>Przynajmniej jedna mała litera (a-z)</li>
                    <li>Przynajmniej jedna cyfra (0-9)</li>
                    <li>Przynajmniej jeden znak specjalny (!@#$%^&*...)</li>
                </ul>
            </div>
            
            <div class="form-group">
                <label for="password">Hasło *</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Potwierdź hasło *</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            
            <button type="submit" name="register_submit" class="btn btn-primary">Zarejestruj się</button>
        </form>
        
        <div class="back-link">
            <a href="logowanie.php">← Powrót do logowania</a>
        </div>
    </div>
</body>
</html>
