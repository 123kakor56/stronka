<?php
session_start();
require_once 'connect.php';

// Sprawdź czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: logowanie.php");
    exit();
}

// Obsługa wylogowania
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: logowanie.php");
    exit();
}

// Pobierz dodatkowe dane użytkownika
$user_data = null;
try {
    $stmt = $conn->prepare("SELECT name, last_name, login, gender, preference FROM user WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Błąd pobierania danych użytkownika.";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zalogowano</title>
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
        
        .logged-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease-out;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .logged-header h1 {
            color: #4CAF50;
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .logged-header p {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .user-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .user-info h2 {
            color: #667eea;
            font-size: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #333;
        }
        
        .info-value {
            color: #666;
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
        
        .btn-logout {
            background: #ff4444;
            color: white;
        }
        
        .btn-logout:hover {
            background: #cc0000;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 68, 68, 0.3);
        }
        
        .welcome-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .welcome-message h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .welcome-message p {
            font-size: 14px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="logged-container">
        <div class="logged-header">
            <h1>Zalogowano!</h1>
            <p>Logowanie przebiegło pomyślnie</p>
        </div>
        
        <?php if ($user_data): ?>
            <div class="welcome-message">
                <h2>Witaj, <?= htmlspecialchars($user_data['name']) ?>!</h2>
                <p>Miło Cię widzieć, <?= htmlspecialchars($user_data['login']) ?></p>
            </div>
            
            <div class="user-info">
                <h2>Twoje informacje</h2>
                
                <div class="info-row">
                    <span class="info-label">Login:</span>
                    <span class="info-value"><?= htmlspecialchars($user_data['login']) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Imię:</span>
                    <span class="info-value"><?= htmlspecialchars($user_data['name']) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Nazwisko:</span>
                    <span class="info-value"><?= htmlspecialchars($user_data['last_name']) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Płeć:</span>
                    <span class="info-value"><?= htmlspecialchars($user_data['gender']) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">System operacyjny:</span>
                    <span class="info-value"><?= htmlspecialchars($user_data['preference']) ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <a href="?logout=1" class="btn btn-logout" onclick="return confirm('Czy na pewno chcesz się wylogować?')">
            Wyloguj się
        </a>
    </div>
</body>
</html>
