<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zad6Wynik</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="lewy" style="width:100%; overflow:auto; text-align: right;">

  <div style="width: 45%;  float:left;  padding:0%;" >
  <label>Imię: <span style="color: #ed2bff; font-size: 16px;"><?php echo $_POST['firstName'] ?? ''; ?></span></label><br><br>
  
  <label>Nazwisko: <span style="color: #ed2bff; font-size: 16px;"><?php echo $_POST['lastName'] ?? ''; ?></span></label><br><br>
  
  <label>Data urodzenia: <span style="color: #ed2bff; font-size: 16px;"><?php echo $_POST['birthDate'] ?? ''; ?></span></label><br><br>
  
  <label>Hasło: <span style="color: #ed2bff; font-size: 16px;"><?php echo $_POST['password'] ?? ''; ?></span></label><br><br>
  
  <label>Ulica: <span style="color: #ed2bff; font-size: 16px;"><?php echo $_POST['street'] ?? ''; ?></span></label><br><br>
  
  <label>Miasto: <span style="color: #ed2bff; font-size: 16px;"><?php echo $_POST['city'] ?? ''; ?></span></label><br><br>
  
  <label>E-mail: <span style="color: #ed2bff; font-size: 16px;"><?php echo $_POST['email'] ?? ''; ?></span></label><br><br>
  </div>

 <div class="srodek">😀</div>

 <div class="prawy" style="width: 45%; float:left; padding:0px;">
    <label>Płeć: <span style="color: #ed2bff; font-size: 16px;"><?php 
        $gender = $_POST['gender'] ?? '';
        echo $gender === 'female' ? 'Baba' : ($gender === 'male' ? 'Chłop' : '');
    ?></span></label><br><br>
    
    <label>Uwagi: <span style="color: #ed2bff; font-size: 16px;"><?php echo nl2br($_POST['comments'] ?? ''); ?></span></label><br><br>
    
    <label>Województwo: <span style="color: #ed2bff; font-size: 16px;"><?php echo $_POST['voivodeship'] ?? ''; ?></span></label><br><br>
    
    <label>Telefon: <span style="color: #ed2bff; font-size: 16px;"><?php echo $_POST['phone'] ?? ''; ?></span></label><br><br>
    
    <br><br>
   
    <a href="Zad6.php" style="display: inline-block; padding: 10px 20px; background-color: #ed2bff; color: white; text-decoration: none; border-radius: 5px;">Powrót do formularza</a><br> <br>
     <a href="Zad6.txt" download style="display: inline-block; padding: 10px 20px; background-color: #ed2bff; color: white; text-decoration: none; border-radius: 5px;">Pobierz Zad6.php</a> <br> <br>
        <a href="Zad6Wynik.txt" download style="display: inline-block; padding: 10px 20px; background-color: #ed2bff; color: white; text-decoration: none; border-radius: 5px;">Pobierz Zad6Wynik.php</a>
    </div>

</div>

</body>
</html>