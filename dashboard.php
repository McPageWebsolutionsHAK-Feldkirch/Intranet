<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dashboard_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users from database
$sql = "SELECT id, name, password FROM users";
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* General styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            color: #333;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background-color: #37474f;
            color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header img {
            height: 50px;
            margin-right: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header .auth-btn {
            background-color: #546e7a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .header .auth-btn:hover {
            background-color: #455a64;
        }
        .content {
            display: flex;
        }
        .sidebar {
            width: 200px;
            padding: 20px;
            background-color: #546e7a;
            color: white;
            height: 100vh;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar h2 {
            margin-top: 0;
            font-size: 18px;
        }
        .sidebar a {
            display: block;
            margin-bottom: 10px;
            color: #b0bec5;
            text-decoration: none;
            transition: color 0.3s;
        }
        .sidebar a:hover {
            color: #eceff1;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #eceff1;
        }
        /* Calendar styles */
        .calendar {
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .calendar .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #37474f;
            color: white;
        }
        .calendar .controls button {
            background-color: #546e7a;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .calendar .controls button:hover {
            background-color: #455a64;
        }
        .calendar table {
            width: 100%;
            border-collapse: collapse;
        }
        .calendar th, .calendar td {
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        .calendar th {
            background-color: #546e7a;
            color: white;
        }
        .calendar td.empty {
            background-color: #f0f4f8;
            cursor: default;
        }
        .calendar td.today {
            background-color: #ffcc80;
        }
        .calendar td.event {
            background-color: #90caf9;
            position: relative;
        }
        .calendar td.event:hover:after {
            content: attr(data-event);
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            background: #546e7a;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
        }
        /* Login Popup styles */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .login-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1001;
            border-radius: 10px;
            width: 300px;
        }
        .login-popup h2 {
            margin: 0 0 20px;
            font-size: 18px;
            color: #37474f;
        }
        .login-popup input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-popup button {
            padding: 10px 20px;
            background-color: #546e7a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-popup button.close-btn {
            background-color: #ff6f61;
        }
        .login-popup button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <div>
            <img src="Bilder/McPage-Logo-Hell.png" alt="Logo">
            <h1>Intranet</h1>
        </div>
        <button class="auth-btn" id="authBtn">Login</button>
    </div>

    <!-- Content Section -->
    <div class="content">
        <div class="sidebar">
            <h2>Artikel</h2>
            <a href="#">Artikel 1</a>
            <a href="#">Artikel 2</a>
            <a href="#">Artikel 3</a>
            <a href="#">Artikel 4</a>
            <a href="#">Artikel 5</a>
        </div>

        <div class="main-content">
            <div class="calendar">
                <div class="controls">
                    <button id="prevMonth">&#8249; Zurück</button>
                    <h3 id="monthYear">Januar 2025</h3>
                    <button id="nextMonth">Weiter &#8250;</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Mo</th>
                            <th>Di</th>
                            <th>Mi</th>
                            <th>Do</th>
                            <th>Fr</th>
                            <th>Sa</th>
                            <th>So</th>
                        </tr>
                    </thead>
                    <tbody id="calendarBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Login Popup -->
    <div class="overlay" id="overlay"></div>
    <div class="login-popup" id="loginPopup">
        <h2>Login</h2>
        <input type="text" id="username" placeholder="Benutzername">
        <input type="password" id="password" placeholder="Passwort">
        <div>
            <button id="loginSubmit">Einloggen</button>
            <button class="close-btn" id="closePopup">Schließen</button>
        </div>
    </div>

    <script>
        const users = <?php echo json_encode($users); ?>;
        const events = {};
        const authBtn = document.getElementById('authBtn');
        const loginPopup = document.getElementById('loginPopup');
        const overlay = document.getElementById('overlay');
        const closePopup = document.getElementById('closePopup');
        const loginSubmit = document.getElementById('loginSubmit');
        const calendarBody = document.getElementById('calendarBody');
        const monthYear = document.getElementById('monthYear');
        const prevMonth = document.getElementById('prevMonth');
        const nextMonth = document.getElementById('nextMonth');

        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth();
        let currentSession = null;

        const monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

        function generateCalendar(year, month) {
            calendarBody.innerHTML = '';
            monthYear.textContent = `${monthNames[month]} ${year}`;
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = new Date();
            let startDay = (firstDay === 0 ? 6 : firstDay - 1);
            let row = document.createElement('tr');

            for (let i = 0; i < startDay; i++) row.appendChild(document.createElement('td')).classList.add('empty');

            for (let day = 1; day <= daysInMonth; day++) {
                if (row.children.length === 7) {
                    calendarBody.appendChild(row);
                    row = document.createElement('tr');
                }
                const cell = document.createElement('td');
                cell.textContent = day;
                const cellDate = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                    cell.classList.add('today');
                }
                if (events[cellDate]) {
                    cell.classList.add('event');
                    cell.setAttribute('data-event', events[cellDate]);
                }
                if (currentSession) {
                    cell.addEventListener('click', () => {
                        const eventText = prompt('Event eingeben für ' + cellDate);
                        if (eventText) {
                            events[cellDate] = eventText;
                            generateCalendar(year, month);
                        }
                    });
                }
                row.appendChild(cell);
            }

            while (row.children.length < 7) row.appendChild(document.createElement('td')).classList.add('empty');

            calendarBody.appendChild(row);
        }

        function toggleLoginPopup(show) {
            loginPopup.style.display = show ? 'block' : 'none';
            overlay.style.display = show ? 'block' : 'none';
        }

        authBtn.addEventListener('click', () => {
            if (currentSession) {
                currentSession = null;
                authBtn.textContent = 'Login';
                alert('Erfolgreich ausgeloggt!');
                generateCalendar(currentYear, currentMonth);
            } else {
                toggleLoginPopup(true);
            }
        });

        closePopup.addEventListener('click', () => toggleLoginPopup(false));

        loginSubmit.addEventListener('click', () => {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const user = users.find(u => u.name === username && u.password === password);
            if (user) {
                currentSession = user;
                authBtn.textContent = 'Logout';
                toggleLoginPopup(false);
                alert(`Willkommen, ${user.name}!`);
                generateCalendar(currentYear, currentMonth);
            } else {
                alert('Benutzername oder Passwort falsch.');
            }
        });

        prevMonth.addEventListener('click', () => {
            currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
            currentYear = currentMonth === 11 ? currentYear - 1 : currentYear;
            generateCalendar(currentYear, currentMonth);
        });

        nextMonth.addEventListener('click', () => {
            currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
            currentYear = currentMonth === 0 ? currentYear + 1 : currentYear;
            generateCalendar(currentYear, currentMonth);
        });

        generateCalendar(currentYear, currentMonth);
    </script>
</body>
</html>
