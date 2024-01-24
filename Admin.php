<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Dashboard 🞄 DocuMan</title>
        <link rel="stylesheet" type="text/css" href="./include/styles/styles.css">
        <link rel="icon" type="image/png" href="./include/styles/images/Logo.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    </head>
    <body>

<?php 
require('.\include\connect\dbcon.php');
if (isset($_SESSION["username"]))
{
    
} else {
    header("location:index.php");
}
    echo '<div class="sidebar">
            <div class="logo"></div>
                <ul class="menu">
                    <li class="active">
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
                <li>
                    <a href="adminauditlog.php">
                        <i class="fas fa-file-lines"></i>
                        <span>Logs</span>
                    </a>
                </li>
            </ul>
        </div>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Admin</span>
                <h2>Welcome, '.$_SESSION["username"].'</h2>
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
                    <h3>'.$_SESSION["username"].'<br><span>Admin</span></h3>
                        <ul>
                            <li><i class="fa-solid fa-user"><a href="adminprofile.php">Profile</a></i></li>
                            <li><i class="fa-solid fa-cog"><a href="#">Settings</a></i></li>
                            <li><i class="fa-solid fa-sign-out-alt"><a href="logout.php">Logout</a></i></li>
                        </ul>
                    </div>
            </div>
        </div>


        <div class="card--container">
            <h3 class="main--title">Todayʼs data</h3>
            <div class="card--wrapper">
                <div class="card--numone">
                    <div class="card--header">
                        <div class="no--user">
        ';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search = $_POST["search"];

            // Query to search for the keyword in multiple columns
            $sql = "SELECT * FROM tbuser WHERE 
                    column1 LIKE :search OR
                    column2 LIKE :search OR
                    column3 LIKE :search";

            $stmt = $pdoConnect->prepare($sql);
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Display search results
            if ($result) {
                echo '<h2>Search Results</h2>';
                foreach ($result as $row) {
                    // Adjust this part to display the results in the desired format
                    echo '<p>' . $row['column1'] . ' - ' . $row['column2'] . ' - ' . $row['column3'] . '</p>';
                }
            } else {
                echo '<p>No results found.</p>';
            }
        }

        $sql = "SELECT COUNT(id) FROM tbuser";

        // Execute the query and get the number of users
        $stmt = $pdoConnect->query($sql);
        $numberOfUsers = $stmt->fetchColumn();

        // Display the number of users
        echo '<span class="title">Total Users <i class="fa-solid fa-user icon-user"></i></span>
                <span class="user--value">' . htmlspecialchars($numberOfUsers) . '</span>
                        </div>
                    </div>
                </div>

                <div class="card--numtwo">
                    <div class="card--header">
                        <div class="no--user">
        ';
            $directory = "./uploads"; // Specify the directory you want to count files in
            $files = scandir($directory); // Get all files and directories
            $fileCount = count($files) - 2; // Subtracting "." and ".." entries
            
            // If you want to count only files and ignore subdirectories
            $filesOnly = array_filter($files, function ($item) use ($directory) {
                return is_file($directory . '/' . $item);
            });
            $fileCount = count($filesOnly);
        
            // shows the number of files 
        echo '<span class="title">Total Files Uploaded <i class="fa-solid fa-file icon-file"></i></span>
                <span class="user--value">'. $fileCount .'</span>
                </div>
            </div>
        </div>
        ';

        // number of Admins
        // SQL query to count the number of Admins
        $sqlAdmins = "SELECT COUNT(id) FROM tbuser WHERE role = 'Admin'";
        $stmtAdmins = $pdoConnect->query($sqlAdmins);
        $numberOfAdmins = $stmtAdmins->fetchColumn();

        // Display the number of Admins
        echo '
            <div class="card--numfour">
                <div class="card--header">
                    <div class="no--user">
                        <span class="title">Admins <i class="fa-solid fa-user-tie icon-admin"></i></span>
                        <span class="user--value">' . htmlspecialchars($numberOfAdmins) . '</span>
                    </div>
                </div>
            </div>
        ';
        
        // number of Active Users
        $sqlActiveUsers = "SELECT COUNT(id) FROM tbuser WHERE a_status = 'Online'";
        $stmtActiveUsers = $pdoConnect->query($sqlActiveUsers);
        $numberOfActiveUsers = $stmtActiveUsers->fetchColumn();

        // Display the number of Active Users
        echo '
            <div class="card--numfive">
                <div class="card--header">
                    <div class="no--user">
                        <span class="title">Active Users <i class="fa-solid fa-user-check icon-active"></i></span>
                        <span class="user--value">' . htmlspecialchars($numberOfActiveUsers) . '</span>
                    </div>
                </div>
            </div>
        ';

        echo '        
        <div id="table-container">
		<table class="sortable">
	    	<thead>
			<tr>
				<th>File Name</th>
				<th>Type</th>
				<th>Size</th>
				<th>Date Uploaded</th>
				<th>Uploaded by</th>
				<th>File Location</th>
			</tr>
	    </thead>
		<tbody>';

        // Adds pretty filesizes
        function pretty_filesize($file) {
            if (file_exists($file) && is_file($file)) {
                $size = filesize($file);
                if ($size < 1024) {
                    $size = $size . " Bytes";
                } elseif ($size < 1048576) {
                    $size = round($size / 1024, 1) . " KB";
                } elseif ($size < 1073741824) {
                    $size = round($size / 1048576, 1) . " MB";
                } else {
                    $size = round($size / 1073741824, 1) . " GB";
                }
            } else {
                $size = "File not found";
            }
            return $size;
        }

        

        // Opens directory
        $myDirectory = opendir("./uploads/");

        // Gets each entry
        while ($entryName = readdir($myDirectory)) {
            $dirArray[] = $entryName;
        }

        // Closes directory
        closedir($myDirectory);

        // Counts elements in array
        $indexCount = count($dirArray);

        // Sorts files
        sort($dirArray);
        

        // Loops through the array of files
        for ($index = 0; $index < $indexCount; $index++) {

            // Decides if hidden files should be displayed, based on the query above.
            if (substr("$dirArray[$index]", 0, 1)) {

                // Resets Variables
                $favicon = "";
                $class = "file";

                // Gets File Names
                $name = $dirArray[$index];
                $namehref = $dirArray[$index];

                // Check if the entry is a directory
                if (is_dir("./uploads/$name")) {
                    // Skip directories
                    continue;
                }

                // Check if the file exists before accessing its properties
                $filePath = "./uploads/" . $namehref;
                if (file_exists($filePath)) {
                    $modtime = date("M j Y g:i A", filemtime($filePath));
                    $timekey = date("YmdHis", filemtime($filePath));
                } else {
                    $modtime = "N/A";
                    $timekey = "0";
                }

                // Separates directories and performs operations on those directories
                if (is_dir($filePath)) {
                    $extn = "Folder";
                    $directoryContents = scandir($filePath);
                    $size = "Directory";
                    $sizekey = "0";
                    $class = "dir";

                    // Gets favicon.ico, and displays it, only if it exists.
                    if (file_exists("$filePath/favicon.ico")) {
                        $favicon = " style='background-image:url($filePath/favicon.ico);'";
                        $extn = "&lt;Website&gt;";
                    }

                    // Cleans up . and .. directories
                    if ($name == ".") {
                        $name = ". (Current Directory)";
                        $extn = "&lt;System Dir&gt;";
                        $favicon = " style='background-image:url($filePath/.favicon.ico);'";
                    }
                    if ($name == "..") {
                        $name = ".. (Parent Directory)";
                        $extn = "&lt;System Dir&gt;";
                    }
                } else {
                    // Gets file extension
                    $extn = pathinfo($filePath, PATHINFO_EXTENSION);

                    // Prettifies file type
                    switch ($extn) {
                        case "png": $extn="PNG Image";
                        break;
                        case "jpg": $extn="JPEG Image";
                        break;
                        case "jpeg": $extn="JPEG Image"; 
                        break;
                        case "svg": $extn="SVG Image"; 
                        break;
                        case "gif": $extn="GIF Image"; 
                        break;
                        case "ico": $extn="Windows Icon"; 
                        break;
                        case "txt": $extn="Text File"; 
                        break;
                        case "log": $extn="Log File"; 
                        break;
                        case "htm": $extn="HTML File"; 
                        break;
                        case "html": $extn="HTML File"; 
                        break;
                        case "xhtml": $extn="HTML File"; 
                        break;
                        case "shtml": $extn="HTML File"; 
                        break;
                        case "php": $extn="PHP Script"; 
                        break;
                        case "js": $extn="Javascript File"; 
                        break;
                        case "css": $extn="Stylesheet"; 
                        break;
                        case "pdf": $extn="PDF Document"; 
                        break;
                        case "xls": $extn="Spreadsheet"; 
                        break;
                        case "xlsx": $extn="Spreadsheet"; 
                        break;
                        case "doc": $extn="Microsoft Word Document"; 
                        break;
                        case "docx": $extn="Microsoft Word Document"; 
                        break;
                        case "zip": $extn="ZIP Archive"; 
                        break;
                        case "htaccess": $extn="Apache Config File"; 
                        break;
                        case "exe": $extn="Windows Executable"; 
                        break;
                        default: 
                        if ($extn != "") {
                            $extn = strtoupper($extn) . " File";
                        } else {
                            if (is_dir($filePath)) {
                                $extn = "Folder"; // Correctly label directories as 'Folder'
                            } else {
                                $extn = "Unknown";
                            }
                        }
                        break;
                        }
                        

                    // Check if the file exists before calling filesize()
                    if (file_exists($filePath) && is_file($filePath)) {
                        $size = pretty_filesize($filePath);
                        $sizekey = filesize($filePath);
                    } else {
                        $size = "File not found";
                        $sizekey = 0;
                    }
                }
                

                if (isset($_SESSION["username"])) {
                    
                    // Handle file upload when the form is submitted
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Find'])) {
                        // Fetch uploader information from the database
                        $stmt = $pdoConnect->prepare("SELECT tbuser.username FROM file_uploads JOIN tbuser ON file_uploads.username = tbuser.username WHERE file_uploads.file_name = :filename");
                        $stmt->execute(['filename' => $namehref]);
                        $uploaderInfo = $stmt->fetch(PDO::FETCH_ASSOC);
                        // Check if uploader info is available
                        $uploaderUsername = $uploaderInfo ? htmlspecialchars($uploaderInfo['username']) : 'Unknown';
                        $searchTerm = isset($_POST['id']) ? $_POST['id'] : '';
                        // Check if the file matches the search criteria
                        if (stripos($name, $searchTerm) !== false || stripos($uploaderUsername, $searchTerm) !== false) {
                            // Output the table row if there is a match
                            echo("
                            <tr class='$class'>
                                <td><a href='javascript:void(0);' onclick='openFilePreview(\"".addslashes($namehref)."\", \"".addslashes($extn)."\")'>".htmlspecialchars($name)."</a></td>
                                <td><a href='./uploads/$namehref'>$extn</a></td>
                                <td sorttable_customkey='$sizekey'><a href='./uploads/$namehref'>$size</a></td>
                                <td sorttable_customkey='$timekey'><a href='./uploads/$namehref'>$modtime</a></td>
                                <td>" . $uploaderUsername . "</td>
                                <td>" . $filePath . "</td>
                            </tr>
                            ");
                        }
                        
                    } else {
                // Fetch uploader information from the database
                $stmt = $pdoConnect->prepare("SELECT tbuser.username FROM file_uploads JOIN tbuser ON file_uploads.username = tbuser.username WHERE file_uploads.file_name = :filename");
                $stmt->execute(['filename' => $namehref]);
                $uploaderInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            
                // Check if uploader info is available
                $uploaderUsername = $uploaderInfo ? htmlspecialchars($uploaderInfo['username']) : 'Unknown';
                

                // Fetch the result
                // Output
                echo("
                <tr class='$class'>
                                    <td><a href='javascript:void(0);' onclick='openFilePreview(\"".addslashes($namehref)."\", \"".addslashes($extn)."\")'>".htmlspecialchars($name)."</a></td>
                                    <td><a href='./uploads/$namehref'>$extn</a></td>
                                    <td sorttable_customkey='$sizekey'><a href='./uploads/$namehref'>$size</a></td>
                                    <td sorttable_customkey='$timekey'><a href='./uploads/$namehref'>$modtime</a></td>
                                    <td>" . $uploaderUsername . "</td>
                                    <td>" . $filePath . "</td>
                                </tr>
                            ");
                    }
                }
            }
        }

        ?>
            </div>
                <script>
                    const menuBtn = document.querySelector('#menu-btn')
                    const menu = document.querySelector('#menu')

                    menuBtn.addEventListener('click', () => {
                        menu.classList.toggle('hide')
                    })

                    document.addEventListener('click', e =>{
                        if(!menu.contains(e.target) && e.target !== menuBtn) {
                            menu.classList.add('hide')
                        }
                    })
                </script>
                </div> 
            </div>  
</body>
</html>
