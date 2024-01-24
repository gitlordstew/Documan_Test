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
        const progressContainer = document.createElement("div");
        const progressBar = document.createElement("div");
        progressContainer.className = "progress-container";
        progressBar.className = "progress-bar";
        li.textContent = file.name;
        li.appendChild(progressContainer);
        progressContainer.appendChild(progressBar);
        fileListItems.appendChild(li);

        const formData = new FormData();
        formData.append("file", file);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "home.php", true);

        xhr.upload.onprogress = (e) => {
            if (e.lengthComputable) {
                const percentage = (e.loaded / e.total) * 100;
                progressBar.style.width = percentage + "%";
            }
        };

        xhr.onload = () => {
            if (xhr.status === 200) {
                li.classList.add("complete");
            }
        };

        xhr.send(formData);
    }
}