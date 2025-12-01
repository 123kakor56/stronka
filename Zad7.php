<?php
require_once 'connect.php';

// Obsługa usuwania studenta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
        $stmt->execute([$_POST['student_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $error_message = "Błąd usuwania studenta: " . $e->getMessage();
    }
}

// Obsługa edycji studenta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_student'])) {
    try {
        $stmt = $conn->prepare("UPDATE student SET nr_indeksu = ?, imie = ?, nazwisko = ?, wiek = ?, ulica = ?, kod_pocztowy = ?, miasto = ?, plec = ? WHERE id = ?");
        $stmt->execute([
            $_POST['nr_indeksu'],
            $_POST['imie'],
            $_POST['nazwisko'],
            $_POST['wiek'],
            $_POST['ulica'],
            $_POST['kod_pocztowy'],
            $_POST['miasto'],
            $_POST['plec'],
            $_POST['student_id']
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $error_message = "Błąd aktualizacji studenta: " . $e->getMessage();
    }
}

// Obsługa dodawania studenta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO student (nr_indeksu, imie, nazwisko, wiek, ulica, kod_pocztowy, miasto, plec) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nr_indeksu'],
            $_POST['imie'],
            $_POST['nazwisko'],
            $_POST['wiek'],
            $_POST['ulica'],
            $_POST['kod_pocztowy'],
            $_POST['miasto'],
            $_POST['plec']
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $error_message = "Błąd dodawania studenta: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Studentów</title>
    <link rel="stylesheet" href="style.css">
    <style>

       

        .student-table {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .student-table h1 {
            color: #8A2BE2;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
        }
        
        .data-table thead {
            background: linear-gradient(135deg, #8A2BE2, #ed2bff);
        }
        
        .data-table th {
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 16px;
            font-weight: 600;
        }
        
        .data-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .data-table tbody tr:hover {
            background-color: #f5e6ff;
            transform: scale(1.01);
        }
        
        .data-table td {
            padding: 12px 15px;
            color: #333;
            font-size: 14px;
            min-width: 80px;
            max-width: 200px;
        }
        
        .data-table tbody tr:last-child {
            border-bottom: none;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }
        
        .error-message {
            background: #ff4444;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 20px auto;
            max-width: 600px;
        }
        
        .success-message {
            background: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 20px auto;
            max-width: 600px;
        }
        
        .add-student-form {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .add-student-form h2 {
            color: #8A2BE2;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            color: #333;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .form-group input,
        .form-group select {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            color: #333;
            width: 100%;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            background: white;
            border-color: #8A2BE2;
            box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.1);
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #8A2BE2, #ed2bff);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin: 0 auto;
            width: 200px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(138, 43, 226, 0.4);
        }
        
        .action-btn {
            padding: 6px 12px;
            margin: 0 3px;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .edit-btn {
            background: #4CAF50;
            color: white;
        }
        
        .edit-btn:hover {
            background: #45a049;
        }
        
        .delete-btn {
            background: #ff4444;
            color: white;
        }
        
        .delete-btn:hover {
            background: #cc0000;
        }
        
        .save-btn {
            background: #ff69b4;
            color: white;
        }
        
        .save-btn:hover {
            background: #ff1493;
        }
        
        .cancel-btn {
            background: #9e9e9e;
            color: white;
        }
        
        .cancel-btn:hover {
            background: #757575;
        }
        
        .edit-input {
            background-color: white;
            width: 100%;
            padding: 12px;
            border: 2px solid #333;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            background-color: white;
            color: black;
            min-height: 40px;
        }
        
        .edit-input:focus {
            outline: none;
            border-color: #000;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }
        
        .edit-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #333;
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
            color: black;
            min-height: 40px;
        }
        
        .edit-select:focus {
            outline: none;
            border-color: #000;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }
        
        .edit-row {
            background-color: #f0f0f0 !important;
        }
        
        .edit-field-row {
            background-color: #ffffff;
        }
        
        .edit-field-row td {
            padding: 15px;
        }
        
        .edit-field-label {
            font-weight: 600;
            color: #8A2BE2;
            min-width: 150px;
            padding-right: 15px;
        }
    </style>
</head>
<body>
    <?php
    if (isset($error_message)) {
        echo '<div class="error-message">' . $error_message . '</div>';
    }
    ?>
    
    <div class="student-table">
        <h1>Lista Studentów</h1>
        
        <?php
        try {
            $stmt = $conn->query("SELECT * FROM student ORDER BY nazwisko, imie");
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($students) > 0) {
                echo '<table class="data-table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Nr indeksu</th>';
                echo '<th>Imię</th>';
                echo '<th>Nazwisko</th>';
                echo '<th>Wiek</th>';
                echo '<th>Ulica</th>';
                echo '<th>Kod pocztowy</th>';
                echo '<th>Miasto</th>';
                echo '<th>Płeć</th>';
                echo '<th>Akcje</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                
                foreach ($students as $student) {
                    $id = $student['id'];
                    echo '<tr id="row-' . $id . '">';
                    echo '<td class="cell-data" data-field="nr_indeksu">' . $student['nr_indeksu'] . '</td>';
                    echo '<td class="cell-data" data-field="imie">' . $student['imie'] . '</td>';
                    echo '<td class="cell-data" data-field="nazwisko">' . $student['nazwisko'] . '</td>';
                    echo '<td class="cell-data" data-field="wiek">' . $student['wiek'] . '</td>';
                    echo '<td class="cell-data" data-field="ulica">' . $student['ulica'] . '</td>';
                    echo '<td class="cell-data" data-field="kod_pocztowy">' . $student['kod_pocztowy'] . '</td>';
                    echo '<td class="cell-data" data-field="miasto">' . $student['miasto'] . '</td>';
                    echo '<td class="cell-data" data-field="plec">' . $student['plec'] . '</td>';
                    echo '<td class="actions-cell">';
                    echo '<button class="action-btn edit-btn" onclick="editRow(' . $id . ')">Edytuj</button>';
                    echo '<form method="POST" style="display:inline;" onsubmit="return confirm(\'Czy na pewno chcesz usunąć tego studenta?\');">';
                    echo '<input type="hidden" name="student_id" value="' . $id . '">';
                    echo '<button type="submit" name="delete_student" class="action-btn delete-btn">Usuń</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="no-data">Brak studentów w bazie danych.</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="error-message">Błąd: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
    
    <div class="add-student-form">
        <h2>Dodaj nowego studenta</h2>
        <form method="POST" action="">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nr_indeksu">Nr indeksu:</label>
                    <input type="text" id="nr_indeksu" name="nr_indeksu" maxlength="6" required>
                </div>
                
                <div class="form-group">
                    <label for="imie">Imię:</label>
                    <input type="text" id="imie" name="imie" maxlength="30" required>
                </div>
                
                <div class="form-group">
                    <label for="nazwisko">Nazwisko:</label>
                    <input type="text" id="nazwisko" name="nazwisko" maxlength="30" required>
                </div>
                
                <div class="form-group">
                    <label for="wiek">Wiek:</label>
                    <input type="number" id="wiek" name="wiek" min="1" max="120" required>
                </div>
                
                <div class="form-group">
                    <label for="ulica">Ulica:</label>
                    <input type="text" id="ulica" name="ulica" maxlength="50" required>
                </div>
                
                <div class="form-group">
                    <label for="kod_pocztowy">Kod pocztowy:</label>
                    <input type="text" id="kod_pocztowy" name="kod_pocztowy" maxlength="6" placeholder="00-000" required>
                </div>
                
                <div class="form-group">
                    <label for="miasto">Miasto:</label>
                    <input type="text" id="miasto" name="miasto" maxlength="50" required>
                </div>
                
                <div class="form-group">
                    <label for="plec">Płeć:</label>
                    <select id="plec" name="plec" required>
                        <option value="">Wybierz...</option>
                        <option value="Mężczyzna">Mężczyzna</option>
                        <option value="Kobieta">Kobieta</option>
                        <option value="Inna">Inna</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" name="add_student" class="submit-btn">Dodaj studenta</button>
        </form>
    </div>
    
    <script>
    let editingRow = null;
    let originalData = {};
    
    function editRow(id) {
        if (editingRow !== null && editingRow !== id) {
            alert('Zakończ edycję bieżącego wiersza przed edycją kolejnego!');
            return;
        }
        
        const row = document.getElementById('row-' + id);
        const cells = row.querySelectorAll('.cell-data');
        
        originalData = {};
        
        // Zapisz oryginalne dane
        cells.forEach(cell => {
            const field = cell.getAttribute('data-field');
            const value = cell.textContent;
            originalData[field] = value;
        });
        
        // Ukryj oryginalny wiersz
        row.style.display = 'none';
        
        // Stwórz wiersze edycji
        const fields = [
            {name: 'nr_indeksu', label: 'Nr indeksu'},
            {name: 'imie', label: 'Imię'},
            {name: 'nazwisko', label: 'Nazwisko'},
            {name: 'wiek', label: 'Wiek'},
            {name: 'ulica', label: 'Ulica'},
            {name: 'kod_pocztowy', label: 'Kod pocztowy'},
            {name: 'miasto', label: 'Miasto'},
            {name: 'plec', label: 'Płeć'}
        ];
        
        let editHTML = '';
        fields.forEach(field => {
            editHTML += `<tr class="edit-field-row" data-edit-for="${id}">`;
            editHTML += `<td colspan="9">`;
            editHTML += `<div style="display: flex; align-items: center; gap: 15px;">`;
            editHTML += `<span class="edit-field-label">${field.label}:</span>`;
            
            if (field.name === 'plec') {
                editHTML += `<select class="edit-select" data-field="${field.name}" style="flex: 1;">
                    <option value="Mężczyzna" ${originalData[field.name] === 'Mężczyzna' ? 'selected' : ''}>Mężczyzna</option>
                    <option value="Kobieta" ${originalData[field.name] === 'Kobieta' ? 'selected' : ''}>Kobieta</option>
                    <option value="Inna" ${originalData[field.name] === 'Inna' ? 'selected' : ''}>Inna</option>
                </select>`;
            } else {
                editHTML += `<input type="text" class="edit-input" value="${originalData[field.name]}" data-field="${field.name}" style="flex: 1;">`;
            }
            
            editHTML += `</div>`;
            editHTML += `</td>`;
            editHTML += `</tr>`;
        });
        
        // Dodaj wiersz z przyciskami
        editHTML += `<tr class="edit-field-row" data-edit-for="${id}">`;
        editHTML += `<td colspan="9" style="text-align: center; padding: 20px;">`;
        editHTML += `<button class="action-btn save-btn" onclick="saveRow(${id})" style="margin: 0 10px;">Zapisz</button>`;
        editHTML += `<button class="action-btn cancel-btn" onclick="cancelEdit(${id})" style="margin: 0 10px;">Anuluj</button>`;
        editHTML += `</td>`;
        editHTML += `</tr>`;
        
        // Wstaw wiersze edycji po ukrytym wierszu
        row.insertAdjacentHTML('afterend', editHTML);
        
        editingRow = id;
    }
    
    function saveRow(id) {
        const editRows = document.querySelectorAll(`tr.edit-field-row[data-edit-for="${id}"]`);
        const inputs = document.querySelectorAll(`tr.edit-field-row[data-edit-for="${id}"] .edit-input, tr.edit-field-row[data-edit-for="${id}"] .edit-select`);
        
        const formData = new FormData();
        formData.append('update_student', '1');
        formData.append('student_id', id);
        
        inputs.forEach(input => {
            const field = input.getAttribute('data-field');
            formData.append(field, input.value);
        });
        
        fetch('', {
            method: 'POST',
            body: formData
        }).then(() => {
            location.reload();
        });
    }
    
    function cancelEdit(id) {
        const row = document.getElementById('row-' + id);
        const editRows = document.querySelectorAll(`tr.edit-field-row[data-edit-for="${id}"]`);
        
        // Usuń wszystkie wiersze edycji
        editRows.forEach(editRow => {
            editRow.remove();
        });
        
        // Pokaż oryginalny wiersz
        row.style.display = '';
        
        editingRow = null;
        originalData = {};
    }
    </script>
</body>
</html>