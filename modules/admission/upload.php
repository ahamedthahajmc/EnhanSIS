<?php
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if (!empty($_FILES['file']) && !empty($_POST['certificate']) && !empty($_POST['status'])) {
//         // Include DB connection
//         include('./DB.php');  // Ensure this is the correct path to your DB.php file
   

//         // Prepare file metadata
//         $certificate = $_POST['certificate'];
//         $status = $_POST['status']; // 'pending' status
//         $uploadDate = date("Y-m-d H:i:s");

//         // Prepare SQL query
//         $stmt = $conn->prepare("INSERT INTO document_uploads (certificate, status, upload_date) VALUES (?, ?, ?)");
//         $stmt->bind_param("sss", $certificate, $status, $uploadDate);

//         // Execute query
//         if ($stmt->execute()) {
//             echo json_encode(['status' => 'success', 'message' => 'Metadata saved successfully.']);
//         } else {
//             error_log("Database error: " . $stmt->error);  // Log error to server log
//             echo json_encode(['status' => 'error', 'message' => 'Failed to save metaData: ' . $stmt->error]);
//         }

//         // Close statement and connection
//         $stmt->close();
//         $conn->close();
//     } else {
//         echo json_encode(['status' => 'error', 'message' => 'Invalid request. Missing file, certificate, or status.']);
//     }
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
// }
?>

<?php
PopTable('header', _documents);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Upload System</title>
    <style>
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
        }

        .btn {
            padding: 8px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            cursor: pointer;
        }

        .btn-upload {
            background-color: #080E4B;
        }

        .btn-upload:hover {
            background-color: #3f7dac;
        }

        .btn-reupload {
            background-color: #ffc107;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            display: none;
            padding: 10px;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .success {
            background-color: #4CAF50;
            color: white;
        }

        .error {
            background-color: #f44336;
            color: white;
        }

        #file-preview-section {
            display: none;
            text-align: center;
        }

        iframe,
        img {
            max-width: 100%;
            max-height: 500px;
        }
    </style>
</head>

<body>
    <div class="table-responsive" id="table-container">
        <h2>Documentations</h2>
        <div id="alert-box" class="alert"></div>
        <table>
            <thead>
                <tr>
                    <th>Certificate Name</th>
                    <th>Upload Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Transfer Certificate (TC)</td>
                    <td id="status-tc">Not Uploaded</td>
                    <td>
                        <input type="file" id="upload-tc" style="display:none;" />
                        <a href="#" class="btn btn-upload" id="upload-tc-btn" onclick="handleUpload('tc')">Upload</a>
                        <a href="#" class="btn btn-primary" id="view-tc-btn" style="display:none;" onclick="viewFile('tc')">View</a>
                        <a href="#" class="btn btn-reupload" id="reupload-tc-btn" style="display:none;" onclick="handleReupload('tc')">Reupload</a>
                    </td>
                </tr>
                <tr>
                    <td>10th Certificate</td>
                    <td id="status-10th">Not Uploaded</td>
                    <td>
                        <input type="file" id="upload-10th" style="display:none;" />
                        <a href="#" class="btn btn-upload" id="upload-10th-btn" onclick="handleUpload('10th')">Upload</a>
                        <a href="#" class="btn btn-primary" id="view-10th-btn" style="display:none;" onclick="viewFile('10th')">View</a>
                        <a href="#" class="btn btn-reupload" id="reupload-10th-btn" style="display:none;" onclick="handleReupload('10th')">Reupload</a>
                    </td>
                </tr>
                <tr>
                    <td>12th Certificate</td>
                    <td id="status-12th">Not Uploaded</td>
                    <td>
                        <input type="file" id="upload-12th" style="display:none;" />
                        <a href="#" class="btn btn-upload" id="upload-12th-btn" onclick="handleUpload('12th')">Upload</a>
                        <a href="#" class="btn btn-primary" id="view-12th-btn" style="display:none;" onclick="viewFile('12th')">View</a>
                        <a href="#" class="btn btn-reupload" id="reupload-12th-btn" style="display:none;" onclick="handleReupload('12th')">Reupload</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="file-preview-section" style="display: none;">
        <div id="file-preview"></div>
        <button class="btn btn-reupload" onclick="showTable()">Back</button>
        <button class="btn btn-upload" onclick="confirmUpload()">Confirm</button>
    </div>

    <script>
        let currentFile = null;
        let currentCertificate = null;

        function handleUpload(certificate) {
            currentCertificate = certificate;
            const fileInput = document.querySelector(`#upload-${certificate}`);
            fileInput.click();

            fileInput.removeEventListener("change", uploadHandler);
            fileInput.addEventListener("change", uploadHandler);
        }

        function uploadHandler(event) {
            currentFile = event.target.files[0];
            if (currentFile) {
                const previewElement = document.getElementById("file-preview");
                const tableContainer = document.getElementById("table-container");
                const previewSection = document.getElementById("file-preview-section");

                tableContainer.style.display = "none";
                previewSection.style.display = "block";

                const fileType = currentFile.type;
                if (fileType.startsWith("image/")) {
                    previewElement.innerHTML = `<img src="${URL.createObjectURL(currentFile)}" alt="Preview">`;
                } else if (fileType === "application/pdf") {
                    previewElement.innerHTML = `<iframe src="${URL.createObjectURL(currentFile)}" frameborder="0" style="width: 100%; height: 500px;"></iframe>`;
                } else {
                    previewElement.innerHTML = `<p>Cannot preview this file type. Please confirm to upload.</p>`;
                }
            }
        }

        function confirmUpload() {
            if (currentFile && currentCertificate) {
                const formData = new FormData();
                formData.append("file", currentFile); // The file being uploaded
                formData.append("certificate", currentCertificate); // Certificate name (e.g., "10th Certificate")
                formData.append("status", "pending"); // Status of the file
                formData.append("flag", "active"); // Add a flag if required
                formData.append("upload_date", new Date().toISOString()); // Add the current timestamp


                fetch("modules/admission/savefile.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.status === "success") {
                            alert("File uploaded successfully!");
                            document.querySelector(`#status-${currentCertificate}`).innerText = "Uploaded";
                            document.querySelector(`#upload-${currentCertificate}-btn`).style.display = "none";
                            document.querySelector(`#view-${currentCertificate}-btn`).style.display = "inline-block";
                            document.querySelector(`#reupload-${currentCertificate}-btn`).style.display = "inline-block";
                            showTable();
                        } else {
                            alert("Failed to upload file: " + data.message);
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert("An error occurred while uploading the file.");
                    });
            } else {
                alert("Please select a file and certificate.");
            }
        }

        function showTable() {
            document.getElementById("table-container").style.display = "block";
            document.getElementById("file-preview-section").style.display = "none";
            currentFile = null;
            currentCertificate = null;
        }

        function viewFile(certificate) {
            const fileUrl = `/files/${certificate}.pdf`; // Adjust to match your actual file paths
            window.open(fileUrl, "_blank");
        }

        function handleReupload(certificate) {
            if (confirm(`Are you sure you want to reupload the ${certificate}?`)) {
                handleUpload(certificate);
            }
        }
    </script>
</body>

</html>