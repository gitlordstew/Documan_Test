<?php
	session_start();
	date_default_timezone_set('Asia/Manila');
	ob_start();
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <link rel="shortcut icon" href="./.favicon.ico">
   <title>Directory Contents</title>

   <link rel="stylesheet" href="./include/styles/styles.css">
	<link rel="icon" type="image/png" href="./include/styles/images/Logo.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
   <script src=".sorttable.js"></script>
   <script src="controlmenu.js"></script>
</head>

<body>
<?php
	require('.\include\connect\dbcon.php');
	if (isset($_SESSION["username"]))
	{
		
	} else {
		header("location:index.php");
	}

	echo'
	<div class="sidebar">
            <div class="logo"></div>
                <ul class="menu">
                    <li>
                        <a href="Admin.php">
                        <i class="fas fa-square"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="active">
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
                <h2>Uploads</h2>
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
		<h3 class="main--title">Drag and Drop</h3>
		<div class="card--wrapper">
		<div class="drop-container" id="drop-area">
                <h1>Drag and Drop Files Here</h1>
                <input type="file" id="fileInput" multiple>
            </div>

			<div class="column">
                <h2>Uploaded Files:</h2>
                <ul id="fileListItems"></ul>
            </div>
		</div>
	</div>
    <script src="upload.js"></script>
<div class="tabular--wrapper">
	<h3 class="main--title">Directory Contents</h3>
	<div class="container-bar">
		<div class="create-folder">
			<form method="post">
				<input class="txt-inputs" type="text" name="foldername" placeholder="Enter folder name" required />
				<input class="create-button" type="submit" value="Create Folder" />
			</form>
		</div>
		<div class="search_add">
            <form action="" method="post">
                <input class="txt-inputs" type="text" name="id" placeholder="Search">
                <input class="tbl_btn" type="submit" name="Find" value="Search">
            </form>
        </div>
	</div>
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
				<th></th>
				<th></th>
			</tr>
	    </thead>
		<tbody>
';

		// Fetch existing access users from the database
		$stmt = $pdoConnect->prepare("SELECT access_users FROM file_uploads WHERE upload_id = :upload_id");
		$stmt->bindParam(':upload_id', $_GET["id"]);
		$stmt->execute();
		$existingAccessUsers = $stmt->fetchColumn();

		// Initialize $existingAccessUsers if it's not set
		$existingAccessUsers = isset($existingAccessUsers) ? $existingAccessUsers : '';

		// Handle form submission for controlling access
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify'])) {
			// Get the selected user and existing access users from the form
			$selectedUser = $_POST['user'];
			$newAccessUsers = $_POST['username'];

			// Update the access users for the specific file in the database
			$stmt = $pdoConnect->prepare("UPDATE file_uploads SET access_users = :access_users WHERE upload_id = :upload_id");
			$stmt->bindParam(':access_users', $newAccessUsers);
			$stmt->bindParam(':upload_id', $_GET['id']);
			$stmt->execute();
		}

		// Handle form submission for controlling access
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify'])) {
			// Get the selected user and existing access users from the form
			$selectedUser = $_POST['user'];
			$newAccessUsers = $_POST['username'];

			// Update the access users for the specific file in the database
			$stmt = $pdoConnect->prepare("UPDATE file_uploads SET access_users = :access_users WHERE upload_id = :upload_id");
			$stmt->bindParam(':access_users', $newAccessUsers);
			$stmt->bindParam(':upload_id', $_GET['id']);
			$stmt->execute();
		}

		// Fetch the user list with the role "client"
		$stmtUserList = $pdoConnect->prepare("SELECT username FROM tbuser WHERE role = 'client'");
		$stmtUserList->execute();
		$userList = $stmtUserList->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handling file uploads
    if (isset($_FILES['file'])) {
        $uploadDir = 'uploads/';
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $uploadedFile = $uploadDir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                echo "<script>alert('File uploaded successfully');</script>";
				$iolog = "Uploaded file: " . basename($_FILES['file']['name']);  // Use basename to get the file name
                $stmt = $pdoConnect->prepare('INSERT INTO auditlog (time_log, username, iolog) VALUES (NOW(), :username, :iolog)');
                $stmt->bindParam(':username', $_SESSION['username']);
                $stmt->bindParam(':iolog', $iolog);
                $stmt->execute();
            } else {
                echo "<script>alert('File upload failed');</script>";
            }
        } else {
            echo "<script>alert('No file was uploaded or file input is missing.');</script>";
        }
    }

    // Handling folder creation
    if (isset($_POST["foldername"])) {
        $folderName = $_POST['foldername'];
        // Sanitize the folder name to avoid directory traversal issues
        $folderName = basename($folderName);

        // Define the path where you want to create the folder
        // Ensure this directory has the correct permissions
        $path = "uploads/" . $folderName;

        // Check if the folder already exists
        if (file_exists($path)) {
            // Folder already exists
            echo "<script>alert('Folder \"$folderName\" already exists.');</script>";
        } else {
            // Attempt to create the folder
            if (mkdir($path, 0777, true)) {
                // Success
                echo "<script>alert('Folder \"$folderName\" created successfully.');</script>";

				// Add an audit log entry for folder creation
				$iolog = "Created folder: $folderName";
				$stmt = $pdoConnect->prepare('INSERT INTO auditlog (time_log, username, iolog) VALUES (NOW(), :username, :iolog)');
				$stmt->bindParam(':username', $_SESSION['username']);
				$stmt->bindParam(':iolog', $iolog);
				$stmt->execute();
	
				if ($stmt) {
					echo "Audit log entry added for folder creation.";
				} else {
					echo "Error adding audit log entry for folder creation.";
				}
				
            } else {
                // Error
                echo "Failed to create folder.";
            }
        }
    }
}

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
								<td><button id='access_btn' class='tbl_btn'>Access</button></td>
								<td><a class='tbl_btn delete_btn' href='" . $_SERVER['PHP_SELF'] . "?delete_file=" . urlencode($namehref) . "' onclick='return confirmDelete()'>Delete</a></td>
								<td><a class='tbl_btn' id='' href=''>Information</a></td>
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
								<td><a id='access_btn' class='tbl_btn' data-filename='$namehref' href='javascript:void(0);'>Access</a></td>
                				<td><a class='tbl_btn delete_btn' href='" . $_SERVER['PHP_SELF'] . "?delete_file=" . urlencode($namehref) . "' onclick='return confirmDelete()'>Delete</a></td>
							</tr>
						");
				}
				
					// Insert the file upload information into the database
					$stmt = $pdoConnect->prepare('INSERT INTO file_uploads (upload_time, username, file_name) VALUES (NOW(), :username, :file_name)');
					$stmt->bindParam(':username', $_SESSION['username']);
					$stmt->bindParam(':file_name', $namehref);
					$stmt->execute();
				}
	}
}
	// delete button //
	if (isset($_GET['delete_file'])) {
		$fileName = basename($_GET['delete_file']); // Sanitize input

		// Delete file from the server
		$filePath = './uploads/' . $fileName;
		if (file_exists($filePath)) {
			unlink($filePath);

			// Log the file deletion
			$iolog = "Deleted file: " . $fileName;
			$stmt = $pdoConnect->prepare('INSERT INTO auditlog (time_log, username, iolog) VALUES (NOW(), :username, :iolog)');
			$stmt->bindParam(':username', $_SESSION['username']);
			$stmt->bindParam(':iolog', $iolog);
			$stmt->execute();
		}

		// Delete file information from the database
		$stmt = $pdoConnect->prepare("DELETE FROM file_uploads WHERE file_name = :fileName");
		$stmt->bindParam(':fileName', $fileName);
		$stmt->execute();

		// Redirect back to the same page to refresh the file list
		header('Location: ' . $_SERVER['PHP_SELF']);
		exit;
	}
	
	ob_end_flush();
	?>
</tbody>
</table>
</div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle menu visibility
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

        // Confirm Delete //
        function confirmDelete() {
            return confirm("Are you sure you want to delete this file?");
        }

        // Access btn
        const accessBtns = document.querySelectorAll('#access_btn');
        const accessclosebtn = document.querySelector('#accessclosebtn');
        const accessUser = document.querySelector('#show_access');

        accessBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation(); // Stop event propagation
                const filename = btn.getAttribute('data-filename');

                // Open the pop-up form
                accessUser.querySelector('h2').innerText = 'CONTROL ACCESS - ' + filename;
                accessUser.classList.toggle('show_access');
            });
        });

        accessclosebtn.addEventListener('click', () => {
            accessUser.classList.toggle('show_access');
        });

        document.addEventListener('click', (e) => {
            if (!accessUser.contains(e.target) && Array.from(accessBtns).some(btn => btn.contains(e.target))) {
                accessUser.classList.remove('show_access');
            }
        });

        // For access textarea
        // Add an event listener to the select element to call the updateAccessTextarea() function when a username is clicked
        document.querySelector('select[name="user"]').addEventListener('change', updateAccessTextarea);

        function updateAccessTextarea() {
            // Get the selected user from the dropdown
            var selectedUser = document.querySelector('select[name="user"]').value;

            // Get the current content of the textarea
            var currentContent = document.getElementById('accessTextarea').value;

            // Check if the textarea is empty
            if (currentContent.trim() === '') {
                // If empty, set the current content as the selected user
                document.getElementById('accessTextarea').value = selectedUser;
            } else {
                // Split the current content into an array using ', ' as the separator
                var usersArray = currentContent.split(', ');

                // Check if the selected user is already present in the array
                if (usersArray.indexOf(selectedUser) === -1) {
                    // Add the selected user to the array
                    usersArray.push(selectedUser);

                    // Join the array back into a string, separated by ', '
                    var newContent = usersArray.join(', ');

                    // Update the textarea with the new content
                    document.getElementById('accessTextarea').value = newContent;
                } else {
                    // Alert or handle the case where the user is already in the list
                    alert('User is already in the list');
                }
            }
        }
    });
</script>

		<div class="access_center">
			<div id="show_access" class="access_menu show_access">
				<h2>CONTROL ACCESS</h2>
				<div id="accessclosebtn" class="close_btn">&times;</div>
				<form action="access.php?id=<?php if (isset($_GET["id"])) { echo $_GET["id"]; } ?>" method="post">
					<input type="hidden" name="id">
					<div class="access_form_element">
						<label for="user">Users: </label>
						<select name="user" required onchange="updateAccessTextarea()">
							<!-- Populate options from the database for users with role "client" -->
							<option value="">All Users</option>
							<?php
							// Query the database to fetch user records with role "client"
							$stmt = $pdoConnect->prepare('SELECT id, username FROM tbuser WHERE role = "client"');
							$stmt->execute();
							$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

							// Loop through the users and add them as options
							foreach ($users as $user) {
								$selected = (isset($_GET["id"]) && $_GET["id"] === $user['username']) ? 'selected' : '';
								echo "<option value=\"{$user['username']}\" {$selected}>{$user['username']}</option>";
							}
							?>
						</select>
					</div>
					<div class="access_form_element">
						<label for="username">People with access</label><br>
						<!-- Pre-fill the textarea with existing access users -->
						<textarea id="accessTextarea" name="username" placeholder="People with access" rows="4" cols="50"><?php echo $existingAccessUsers; ?></textarea>
					</div>
					<div id="accesssavebtn" class="access_save_btn">
						<input class="access_user_btn" type="submit" name="modify" value="access">
					</div>
				</form>
            </div>
        </div>			
			<div class="info-content">
				<div class=" info-container">
					<div class="top-info">
						<p>FILENAME</p>
						<i class="fa-solid fa-xmark"></i>
					</div>
					<div class="info-buttons">
						
					</div>
				</div>
			</div>
</body>
</html>