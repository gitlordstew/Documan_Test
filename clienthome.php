<?php
	session_start();
	date_default_timezone_set('Asia/Manila');
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>My Files</title>
        <link rel="stylesheet" type="text/css" href="./include/styles/styles.css">
        <link rel="icon" type="image/png" href="./include/styles/images/Logo.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
        <script src=".sorttable.js"></script>
        <script src="controlmenu.js"></script>
    </head>
<body class="cli-body">
<?php
	require('.\include\connect\dbcon.php');
	if (isset($_SESSION["username"]))
	{
		
	} else {
		header("location:index.php");
	}
	echo'
    <div class="cli-main--content">  
    <header class="client-header">
        <div class="client-container">
            <div class="client-logo">
                <a href=""><img class="cli-logo" src="./include/styles/images/Logo.png" alt=""><span class="cli-logo-title">Files</span></a>
            </div>
            <div class="client-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" placeholder="Search">
            </div>
            <div class="profile" >
                <img id="menu-btn" src="./include/styles/images/profile.jpg" alt=""/>
            </div>
            <div id="menu" class="cli-profile_menu hide">
                    <h3>'.$_SESSION["username"].'<br><span>Welcome</span></h3>
                        <ul>
                            <li><i class="fa-solid fa-user"><a id="update_profile" href="profile.php?username=<?php if(isset($_GET["username"])){echo $_GET["username"];}?>Profile</a></i></li>
                            <li><i class="fa-solid fa-cog"><a href="#">Settings</a></i></li>
                            <li><i class="fa-solid fa-sign-out-alt"><a href="logout.php">Logout</a></i></li>
                        </ul>
                    </div>
        </div>
    </header>
    <div class="cli-tabular--wrapper">
	<h3 class="cli-main--title">Your Files</h3>
	<div id="table-container"><br>
		<table class="sortable">
	    	<thead>
			<tr>
				<th>File Name</th>
				<th>Type</th>
				<th>Size</th>
				<th>Date Uploaded</th>
				<th>Uploaded by</th>
				<th>Comment</th>
			</tr>
	    </thead>
		<tbody>
';

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
				$stmt = $pdoConnect->prepare('INSERT INTO file_uploads (upload_time, username, file_name) VALUES (NOW(), :username, :file_name)');
				$stmt->bindParam(':username', $_SESSION['username']);
				$stmt->bindParam(':file_name', $namehref);
				$stmt->execute();
			}

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
				<td sorttable_customkey='$sizekey'>$size</a></td>
				<td sorttable_customkey='$timekey'>$modtime</a></td>
				<td>" . $uploaderUsername . "</td>
				<td><a class='tbl_btn' id='' href=''>Comment</a></td>
			</tr>
		");
	}
}	

		

	?>
</tbody>
</table>
</div>
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
</body>
</html>