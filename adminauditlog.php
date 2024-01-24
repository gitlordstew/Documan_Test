<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Include database connection
require('.\include\connect\dbcon.php');

// Redirect to login if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('location:index.php');
    exit();
}

// Function to validate input
function validate($input)
{
    // Perform your validation or sanitization logic here
    // For example, you can use trim and htmlspecialchars to sanitize input
    return htmlspecialchars(trim($input));
}

// Process filter form
$selectedLogType = isset($_GET['iolog']) ? validate($_GET['iolog']) : '';

// Build the SQL query based on the selected log type
$pdoQuery = 'SELECT * FROM auditlog';
if (!empty($selectedLogType)) {
    switch ($selectedLogType) {
        case 'Logged in.':
            $pdoQuery .= " WHERE iolog = 'Logged in.'";
            break;
        case 'Logged Out.':
            $pdoQuery .= " WHERE iolog = 'Logged Out.'";
            break;
        case 'Uploaded file:':
            $pdoQuery .= " WHERE iolog LIKE 'Uploaded file:%'";
            break;
    }
}
$pdoQuery .= ' ORDER BY time_log DESC';

// Execute the query
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->execute();

// Fetch the results into an array
$logRows = $pdoResult->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs</title>
    <link rel="stylesheet" type="text/css" href="./include/styles/styles.css">
    <link rel="icon" type="image/png" href="./include/styles/images/Logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div class="sidebar">
            <div class="logo"></div>
                <ul class="menu">
                    <li>
                        <a href="Admin.php">
                        <i class="fas fa-square"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                <a href="adminupload.php">
                    <i class="fas fa-upload"></i>
                    <span>Uploads</span>
                </a>
                </li>
                <li>
                    <a href=".\crud\adminread.php">
                        <i class="fas fa-user-lock"></i>
                        <span>Users & Access</span>
                    </a>
                </li>
                <li class="active">
                    <a href="adminauditlog.php">
                        <i class="fas fa-file-lines"></i>
                        <span>Logs</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Logs</h2>
        </div>
            <div class="user--info">
                <div class="nav--icons">
                    <ul>
                        <li>
                            <a href="">
                            <i class="fa-solid fa-message"></i>
                            </a>
                        </li>
                        <li>
                            <a href="" class="notification">
                            <i class="fa-solid fa-bell"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="profile" >
                <img id="menu-btn" src="./include/styles/images/profile.jpg" alt=""/>
                </div>
                    <div id="menu" class="profile_menu hide">
                <?php
                    echo'
                    <h3>'. $_SESSION["username"] .'<br><span>Super Admin</span></h3>
                    ';
                ?>
                        <ul>
                            <li><i class="fa-solid fa-user"><a href="adminprofile.php">Profile</a></i></li>
                            <li><i class="fa-solid fa-cog"><a href="#">Settings</a></i></li>
                            <li><i class="fa-solid fa-sign-out-alt"><a href="logout.php">Logout</a></i></li>
                        </ul>
                    </div>
            </div>
        </div>

        <div class="card--container">
            <h3 class="main--title">Audit Log</h3>
            <div>
                <p>This is the Audit Log Dashboard for your projects.</p>
            </div>

            <div>
                <form action="" method="GET">
                    <div class="row">
                        <div class="filter-container">
                            <label for="log-filter">Filter Logs:</label>
                            <select id="log-filter" name="iolog" required class="form-select">
                                <option value="">Logs Filters</option>
                                <option value="Logged in." <?= $selectedLogType == "Logged in." ? "selected" : "" ?>>Logged in</option>
                                <option value="Logged Out." <?= $selectedLogType == "Logged Out." ? "selected" : "" ?>>Logged Out</option>
                                <option value="Uploaded file:" <?= $selectedLogType == "Uploaded file:" ? "selected" : "" ?>>Uploaded files</option>
                            </select>
                            <button type="submit" class="tbl_btn">Filter</button>
                            <a href="adminauditlog.php" class="tbl_btn">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

        <!-- Table to display logs -->
        <table id="logs-table">
            <thead>
                <tr>
                    <th>Activity ID</th>
                    <th>Username</th>
                    <th>Date</th>
                    <th>Log</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($logRows as $row) {
                    echo "<tr>";
                    echo "<td>{$row['activity_id']}</td>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['time_log']}</td>";
                    echo "<td>{$row['iolog']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</div>
    <script>
            const menuBtn = document.querySelector('#menu-btn');
            const menu = document.querySelector('#menu');
            
            menuBtn.addEventListener('click', () => {
                menu.classList.toggle('hide');
            });
            
            document.addEventListener('click', e => {
                if (!menu.contains(e.target) && e.target !== menuBtn) {
                    menu.classList.add('hide');
                }
            });
            
            // Filter logs
            document.getElementById('log-filter').addEventListener('change', function () {
                var value = this.value;
                var rows = document.getElementById('logs-table').getElementsByTagName('tr');
            
                for (var i = 1; i < rows.length; i++) { // Start from index 1 to skip the header row
                    var logType = rows[i].getAttribute('data-log-type');
                    var logRow = rows[i];
            
                    if (value === 'all' || logType === value) {
                        logRow.style.display = ''; // Show the row
                    }
                }
            });
        </script>

    </body>
</html>