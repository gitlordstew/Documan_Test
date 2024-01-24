const dropArea = document.getElementById("drop-area");
const fileListItems = document.getElementById("fileListItems");
const progressBar = document.getElementById("progress-bar");

dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.classList.add("drag-over");
});

dropArea.addEventListener("dragleave", () => {
    dropArea.classList.remove("drag-over");
});

dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.classList.remove("drag-over");

    const files = e.dataTransfer.files;
    uploadFiles(files);
});

const fileInput = document.getElementById("fileInput");

fileInput.addEventListener("change", (e) => {
    const files = e.target.files;
    uploadFiles(files);
});

function uploadFiles(files) {
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const li = document.createElement("li");
        li.textContent = file.name;
        fileListItems.appendChild(li);

        const formData = new FormData();
        formData.append("file", file);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "upload.php", true);

        xhr.upload.onprogress = (e) => {
            if (e.lengthComputable) {
                const percentage = (e.loaded / e.total) * 100;
                progressBar.style.width = percentage + "%";
            }
        };

        xhr.onload = () => {
            if (xhr.status === 200) {
                li.classList.add("complete");
                console.log("File upload complete. Reloading page...");
                window.location.reload(true); // Reload the page
            }
        };

        xhr.send(formData);
    }
}

// document preview

function openFilePreview(fileName, extn) {
    // Construct the file path based on the assumed fileName
    let filePath = './uploads/' + fileName;

    if (extn === 'PDF Document') {
        // Open the PDF or document in a new window
        window.open(filePath, '_blank');
    } else if (extn === "JPG Image" || extn === "JPEG Image" || extn === "PNG Image" || extn === "GIF") {
        // If it's an image, display the image
        let content = '<img src="' + filePath + '" alt="File Preview" style="max-width:100%;">';

        // Create the pop-up structure if it doesn't exist
        let popup = document.getElementById('filePreviewPopup');
        if (!popup) {
            popup = document.createElement('div');
            popup.id = 'filePreviewPopup';
            popup.className = 'file-preview-popup';
            document.body.appendChild(popup);
        }
        popup.style.display = 'block';

        // Set the popup content
        popup.innerHTML = '<h2>Preview of ' + fileName + '</h2>' + content + '<p><a href="' + filePath + '" download>Download file</a>.</p><button class="dropbtn" onclick="closeFilePreview()">Close</button>';
    } else if (extn === 'Text File' || extn === 'HTML File' || extn === 'Stylesheet' || extn === 'Javascript File') {
        // If it's a text-based file, fetch and display the text content
        fetch(filePath)
            .then(response => response.text())
            .then(text => {
                document.querySelector('.file-content').textContent = text;
            });

        // Create the pop-up structure if it doesn't exist
        let popup = document.getElementById('filePreviewPopup');
        if (!popup) {
            popup = document.createElement('div');
            popup.id = 'filePreviewPopup';
            popup.className = 'file-preview-popup';
            document.body.appendChild(popup);
        }
        popup.style.display = 'block';

        // Set the popup content
        popup.innerHTML = '<h2>Preview of ' + fileName + '</h2><div class="file-content">Loading...</div><p><a href="' + filePath + '" download>Download file</a>.</p><button class="dropbtn" onclick="closeFilePreview()">Close</button>';
    } else {
        // For other file types, provide a download link
        let content = '<p>Preview not available for this file type. <a href="' + filePath + '" download>Download file</a>.</p>';

        // Create the pop-up structure if it doesn't exist
        let popup = document.getElementById('filePreviewPopup');
        if (!popup) {
            popup = document.createElement('div');
            popup.id = 'filePreviewPopup';
            popup.className = 'file-preview-popup';
            document.body.appendChild(popup);
        }
        popup.style.display = 'block';

        // Set the popup content
        popup.innerHTML = '<h2>Preview of ' + fileName + '</h2>' + content + '<button class="dropbtn" onclick="closeFilePreview()">Close</button>';
    }
}

function closeFilePreview() {
    var popup = document.getElementById('filePreviewPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}
