<?php
PopTable("header", _dynamic);
include 'DB.php';

// 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    h2 {
      color: #444;
    }



    button {
      background-color: #080E4B;
      color: white;
      cursor: pointer;
    }

    .btn-save {
      background-color: #ffc107;
    }

    .btn-cancel {
      background-color: #6c757d;
    }

    button:hover {
      background-color: #3f7dac;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: #fff;
    }

    table th,
    table td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
    }

    table th {
      background-color: #f2f2f2;
    }

    .loading {
      text-align: center;
      font-size: 18px;
      color: #666;
    }

    /* #certificateTableSection {
            display: none;
        } */

    #newCertificateForm {
      display: none;
    }

    .preview-section {
      display: none;
      margin-top: 20px;
      padding: 20px;
      border: 1px solid #ccc;
      background: #f9f9f9;
    }

    .preview-section img {
      max-width: 100%;
      max-height: 400px;
    }

    .preview-section a {
      color: #007bff;
      text-decoration: none;
    }

    .dis-flex {
      display: flex;
      margin-top: 15px;
    }

    .flex-align-center {
      display: flex;
      align-items: center;
    }

    .wid-235 {
      width: 235px;
    }
  </style>
</head>

<body>
  <!-- Student ID Input Form -->
  <?php
  if (User('PROFILE') == 'admin') {
  ?><!-- Student ID Form (Initially Visible) -->
    <!-- Student ID Form (Initially Visible) -->
    <div id="studentIdForm">
      <div class="form-horizontal m-b-0">
        <?php Search('Search'); ?>
        <hr />
        <div style="display: flex; justify-content: flex-end;">
          <button type="submit" onclick="fetchStudentDetails()" class="btn btn-primary">Search</button>
        </div>
      </div>
    </div>

    <!-- Certificates Table Section (Initially Hidden) -->
    <div id="certificateTableSection" style="display: none;">
      <button class="btn btn-primary" onclick="showNewCertificateForm()" id="addCertificateBtn"> + </button>
      <button class="btn btn-primary" onclick="backbutton()" id="Backbtn"> &larr; </button>

      <div style="max-height: 400px; overflow-y: auto;">
        <table class="table table-bordered table-responsive">
          <thead id="certificateTableHead">
            <tr>
              <th>Certificate Name</th>
              <th>Upload Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="certificatesTable">
            <!-- Dynamically populated rows -->

          </tbody>
        </table>
      </div>
    </div>


    <p class="loading" id="loadingMessage" style="display: none;">Loading certificates...</p>

    <!-- Preview Section (Initially Hidden) -->
    <div class="preview-section" id="previewSection" style="display: none;">
      <div id="filePreview"></div>
      <!-- When previewing a new file to upload, show Confirm -->
      <div class='dis-flex'>
        <button id="confirmBtn" class="btn btn-save" style="background-color:#080E4B; display: none; margin-right:10px">Confirm</button>
        <!-- For both preview & view, this Cancel button acts as "Close" -->
        <button id="cancelPreviewBtn" class="btn btn-cancel">Close</button>
      </div>
    </div>

    <!-- New Certificate Form (Initially Hidden) -->
    <div id="newCertificateForm" style="display: none;">
      <h1 style="text-align: left;">New Document</h1>
      <hr>
      <form id="certificateForm">
        <div class="form-row">
          <div class="form-group">
            <label for="certificate-name">Certificate Name</label>
            <input type="text" class="form-control" style="width:35%" id="certificate-name" name="certificate_name" required>
          </div>
          <button type="submit" class="btn btn-save" style="background-color:#080E4B" id="save-btn">Save</button>
          <button type="button" class="btn btn-cancel" id="cancelNewCertBtn" onclick="cancelNewCertificate()">Cancel</button>
        </div>
      </form>
    </div>

    <script>
      document.getElementById('certificateForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const certificateName = document.getElementById('certificate-name').value;

        // Send data using fetch API
        fetch('modules/admission/add_certificate.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
              certificate_name: certificateName
            })
          })
          .then(response => response.json()) // Parse the JSON response
          .then(data => {
            // Show alert with the message from the response
            alert(data.message);

            // Only hide the form if the request was successful
            if (data.success) {

              // document.getElementById('newCertificateForm').style.display='none';
              // document.getElementById('certificateTableSection').style.display = 'block'; // Show the form
              location.reload();

            }
          })
          .catch(error => {
            alert('An error occurred. Please try again later.');
          });
      });


      function deleteCertificate(certificateId, certificateName, studentId, filePath) {
        console.log("Deleting certificate with ID:", certificateId); // Log the ID
        $.ajax({
          url: 'modules/admission/delete_certificate.php',
          type: 'POST',
          data: {
            id: certificateId // Send the id as 'id' to match PHP code
          },
          success: function(data) {
            const response = JSON.parse(data);
            if (response.success) {
              alert("Certificate deleted successfully!");
              const row = document.getElementById(`certificateRow_${certificateId}`);
              if (row) {
                row.remove(); // Remove the entire <tr> row
              } else {
                alert("Row not found.");
              }
            } else {
              alert("Failed to delete certificate.");
            }
          },
          error: function() {
            alert("Error deleting certificate.");
          }
        });
      }
    </script>




    <script>
      // Fetch and display student certificates
      // Function to fetch and display student details or certificates
      function fetchStudentDetails() {
        let studentId = document.getElementById('student_id').value.trim();
        let first_name = $('#first').val();
        let last_name = $('#last').val();
        let grade = $('#grade').val();
        let section = $('#section').val();

        // Show loading spinner/message
        // document.getElementById('loadingMessage').style.display = 'block';

        if (studentId) {
          // Fetch certificates by Student ID
          document.getElementById('Backbtn').style.display = 'none';

          fetch(`modules/admission/fetch_certificates.php?student_id=${studentId}&first_name=${first_name}&last_name=${last_name}`)
            .then((response) => response.json())
            .then((certificates) => {
              document.getElementById('loadingMessage').style.display = 'none';

              const tableBody = document.getElementById('certificatesTable');
              tableBody.innerHTML = ''; // Clear previous data

              if (!certificates.length) {
                tableBody.innerHTML = `<tr><td colspan="3">No certificates found for this student.</td></tr>`;
                return;
              }

              const rows = certificates.map((cert) =>{
                return(`

         <tr id="certificateRow_${cert.certificate_id}">
    <td>
        <button class="btn btn-danger text-white" onclick="deleteCertificate('${cert.certificate_id}', '${cert.certificate_name}', '${studentId}', '${cert.file_path}')">✖</button>
        ${cert.certificate_name}
    </td>
    <td>${cert.upload_status}</td>
    <td>
        ${cert.upload_status === 'Uploaded' 
            ? `
                <button class="btn btn-save" style="background-color:blue" onclick="viewCertificate('${cert.certificate_name}', '${studentId}', '${cert.file_path}')">View</button>
                <button class="btn btn-save" onclick="reuploadCertificate('${cert.certificate_name}', '${studentId}')">Edit</button>`
            : `
                <div class="flex-align-center">
                    <input type="file" id="file_${cert.certificate_name}" onchange="previewFile('${cert.certificate_name}','with_id')" />
                    <button class="btn btn-primary" onclick="uploadCertificate('${cert.certificate_name}', '${studentId}')">Upload</button>
                </div>`
        }
    </td>
</tr>

        `)}).join('');
              tableBody.innerHTML = rows;

              // Hide the student search form and show the certificates table
              $('#studentIdForm').hide();
              $('#certificateTableSection').show();
            })
            .catch((error) => {
              console.error('Error fetching certificates:', error);
              alert('An error occurred while fetching certificates.');
            });
        } else if (grade) {
          // Hide certificate table header and "+" button
          document.getElementById('certificateTableHead').style.display = 'none';
          document.getElementById('addCertificateBtn').style.display = 'none';
          document.getElementById('Backbtn').style.display = 'none';

          // Fetch students by Grade and Section
          fetch(`modules/admission/SearchGradeForDocuments.php?grade=${grade}&section=${section}`)
            .then((response) => response.json())
            .then((data) => {
              document.getElementById('loadingMessage').style.display = 'none';

              if (data.success) {
                let tableHtml = `
            <table class="table table-bordered " id="gradeTableSection">
              <thead>
                <tr class="bg-grey-200">
                  <th>Student Name</th>
                  <th>Student Id</th>
                  <th>Grade</th>
                  <th>Section</th>
                </tr>
              </thead>
              <tbody>`;
                data.students.forEach(function(student) {
                  tableHtml += `
              <tr>
                <td>
                  <a href="#" class="student-link" data-id="${student.student_id}" data-name="${student.student_name}" onclick="selectStudentAndFetchCertificates('${student.student_id}', '${student.student_name}')">${student.student_name}</a>
                </td>
                <td>${student.student_id}</td>
                <td>${student.grade_title}</td>
                <td>${student.section_name}</td>
              </tr>`;
                });
                tableHtml += '</tbody></table>';

                // Hide the student search form, show the grade section table, and hide the grade search UI
                $('#studentIdForm').hide();
                $('#certificateTableSection').show();
                $('#certificatesTable').html(tableHtml);
              } else {
                document.getElementById('gradeResult').innerHTML = `<p>${data.message || 'No students found for the selected grade and section.'}</p>`;
              }

            })
            .catch((error) => {
              console.error('Error fetching data:', error);
              document.getElementById('gradeResult').innerHTML = '<p>Error fetching data. Please try again later.</p>';
            });
        } else if (first_name || last_name) {
          // Hide certificate table header and "+" button
          document.getElementById('certificateTableHead').style.display = 'none';
          document.getElementById('addCertificateBtn').style.display = 'none';
          document.getElementById('Backbtn').style.display = 'none';

          // Fetch students by Grade and Section
          fetch(`modules/admission/SearchGradeForDocuments.php?first_name=${first_name}&last_name=${last_name}`)
            .then((response) => response.json())
            .then((data) => {
              document.getElementById('loadingMessage').style.display = 'none';

              if (data.success) {
                let tableHtml = `
            <table class="table table-bordered " id= "gradeTableSection ">
              <thead>
                <tr class="bg-grey-200">
                  <th>Student Name</th>
                  <th>Student Id</th>
                  <th>Grade</th>
                  <th>Section</th>
                </tr>
              </thead>
              <tbody>`;
                data.students.forEach(function(student) {
                  tableHtml += `
              <tr>
                <td>
                  <a href="#" class="student-link" data-id="${student.student_id}" data-name="${student.student_name}" onclick="selectStudentAndFetchCertificates('${student.student_id}', '${student.student_name}')">${student.student_name}</a>
                </td>
                <td>${student.student_id}</td>
                <td>${student.grade_title}</td>
                <td>${student.section_name}</td>
              </tr>`;
                });
                tableHtml += '</tbody></table>';

                // Hide the student search form, show the grade section table, and hide the grade search UI
                $('#studentIdForm').hide();
                $('#certificateTableSection').show();
                $('#certificatesTable').html(tableHtml);
              } else {
                document.getElementById('gradeResult').innerHTML = `<p>${data.message || 'No students found for the selected grade and section.'}</p>`;
              }

            })
            .catch((error) => {
              console.error('Error fetching data:', error);
              document.getElementById('gradeResult').innerHTML = '<p>Error fetching data. Please try again later.</p>';
            });
        } else {
          alert('Please enter a Student ID or select a Grade.');
          document.getElementById('loadingMessage').style.display = 'none';
        }
      }

      function selectStudentAndFetchCertificates(studentId, studentName) {

        const certificateTableHead = document.getElementById('certificateTableHead');
        const addCertificateBtn = document.getElementById('addCertificateBtn');
        const BackBtn = document.getElementById('Backbtn');

        $('#gradeTableSection').css('display', 'none'); // Show the grade table
        $('#certificateTableSection').css('display', 'block'); // Hide the certificate table

        // Show the table header and the + button
        if (certificateTableHead) {
          certificateTableHead.style.display = 'table-header-group'; // Ensure the table header is visible
        }
        if (addCertificateBtn) {
          addCertificateBtn.style.display = 'inline-block'; // Ensure the + button is visible
        }

        if (BackBtn) {
          BackBtn.style.display = 'inline-block';
        }
        // Fetch certificates for the selected student
        fetch(`modules/admission/fetch_certificates.php?student_id=${studentId}`)
          .then((response) => response.json())
          .then((certificates) => {
            const tableBody = document.getElementById('certificatesTable');
            tableBody.innerHTML = ''; // Clear previous data

            if (certificates.length === 0) {
              tableBody.innerHTML = `<tr><td colspan="3">No certificates found for this student.</td></tr>`;
              return;
            }

            const rows = certificates.map((cert) => `
        <tr>
          <td>${cert.certificate_name}</td>
          <td>${cert.upload_status}</td>
          <td>
            ${cert.upload_status === 'Uploaded'
              ? `<button class="btn btn-save" style= "background-color:blue" onclick="viewCertificate('${cert.certificate_name}', '${studentId}', '${cert.file_path}')">View</button>
                 <button class="btn btn-save" onclick="reuploadCertificate('${cert.certificate_name}', '${studentId}')">Edit</button>`
              : `<div class="flex-align-center">
                   <input type="file" id="file_${cert.certificate_name}" onchange="previewFile('${cert.certificate_name}',${studentId})" />
                   <button class="btn btn-primary" onclick="uploadCertificate('${cert.certificate_name}', '${studentId}')">Upload</button>
                 </div>`
            }
          </td>
        </tr>
      `).join('');

            tableBody.innerHTML = rows;
          })
          .catch((error) => {
            console.error('Error fetching certificates:', error);
            alert('An error occurred while fetching certificates.');
          });
      }

      function showNewCertificateForm() {
        document.getElementById('certificateTableSection').style.display = 'none'; // Hide the certificate table
        document.getElementById('newCertificateForm').style.display = 'block'; // Show the form
      }

      function cancelNewCertificate() {
        document.getElementById('newCertificateForm').style.display = 'none'; // Hide the form
        document.getElementById('certificateTableSection').style.display = 'block'; // Show the certificate table
      }

      function backbutton() {
        fetchStudentDetails();
      }


      // Function to view an already uploaded certificate
      function viewCertificate(certificateName, studentId, filePath) {
        if (filePath) {
          window.open(filePath, '_blank'); // Opens the file in a new tab
        } else {
          alert('No file available to view for this certificate.');
        }
      }



      // Function to preview a file before uploading (for certificates not yet uploaded)
      function previewFile(certificateName, studentID) {
        const fileInput = document.getElementById(`file_${certificateName}`);
        const file = fileInput.files[0];
        const studentId = studentID !== "with_id" ? studentID : document.getElementById('student_id').value;
        // console.log("student id:" + studentId);

        if (file) {
          const previewSection = document.getElementById('previewSection');
          const filePreview = document.getElementById('filePreview');
          filePreview.innerHTML = ''; // Clear previous content
          const reader = new FileReader();

          reader.onload = function() {
            if (file.type.startsWith('image/')) {
              const img = document.createElement('img');
              img.src = reader.result;
              img.style.maxWidth = '100%';
              img.style.height = 'auto';
              filePreview.appendChild(img);
            } else if (file.type === 'application/pdf') {
              const pdfBlob = new Blob([file], {
                type: 'application/pdf',
              });
              const pdfUrl = URL.createObjectURL(pdfBlob);
              const iframe = document.createElement('iframe');
              iframe.src = pdfUrl;
              iframe.width = '100%';
              iframe.height = '500px';
              iframe.style.border = 'none';
              filePreview.appendChild(iframe);
            } else {
              alert('Unsupported file type');
              return;
            }

            // For file preview before upload, show Confirm button
            const confirmBtn = document.getElementById('confirmBtn');
            confirmBtn.style.display = 'block';

            // Hide certificate table and show preview section
            document.getElementById('certificateTableSection').style.display = 'none';
            previewSection.style.display = 'block';

            // Store selected file info globally for later upload
            window.selectedFile = file;
            window.selectedCertificate = certificateName;
            window.studentId = studentId;

            // Add event listener for the Confirm button
            confirmBtn.addEventListener('click', function handleUpload() {
              const formData = new FormData();
              formData.append('certificate_file', window.selectedFile);
              formData.append('certificate_name', window.selectedCertificate);
              formData.append('student_id', window.studentId);
              console.log("called this one");

              fetch('modules/admission/upload_certificate.php', {
                  method: 'POST',
                  body: formData,
                })
                .then((response) => response.json())
                .then((data) => {
                  if (data.success) {
                    alert('Certificate uploaded successfully.');

                    // Hide the preview section and show the certificate table again
                    document.getElementById('previewSection').style.display = 'none';
                    document.getElementById('certificateTableSection').style.display = 'block';

                    // Clear the previous file preview
                    document.getElementById('filePreview').innerHTML = '';

                    // Get the certificate table body element
                    const tableBody = document.getElementById('certificatesTable');

                    // Loop through all the rows and find the one for the uploaded certificate
                    const rows = tableBody.getElementsByTagName('tr');
                    for (let i = 0; i < rows.length; i++) {
                      const row = rows[i];
                      const cells = row.getElementsByTagName('td');
                      const certificateNameCell = cells[0]; // The first cell contains the certificate name

                      // Check if this row is for the uploaded certificate
                      if (certificateNameCell && certificateNameCell.textContent === window.selectedCertificate) {
                        // Update the row for this certificate
                        cells[1].textContent = 'Uploaded'; // Change upload status
                        cells[2].innerHTML = `
                    <button class="btn btn-save" style="background-color:blue" onclick="viewCertificate('${window.selectedCertificate}', '${window.studentId}', '${data.file_path}')">View</button>
                    <button class="btn btn-save" onclick="reuploadCertificate('${window.selectedCertificate}', '${window.studentId}')">Edit</button>
                  `;
                        break; // Exit the loop once the row is updated
                      }
                    }

                    // Remove the click event listener to prevent duplicate uploads
                    confirmBtn.removeEventListener('click', handleUpload);
                  } else {
                    alert('Failed to upload certificate. Please try again.');
                  }
                })
                .catch((error) => {
                  console.error('Error uploading certificate:', error);
                  alert('An error occurred while uploading the certificate. Please try again.');
                });
            });
          };

          // Read the file as Data URL or ArrayBuffer based on type
          if (file.type.startsWith('image/')) {
            reader.readAsDataURL(file);
          } else if (file.type === 'application/pdf') {
            reader.readAsArrayBuffer(file);
          }
        }
      }


      // Function for reuploading a certificate
      function reuploadCertificate(certificateName, studentId) {
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*,application/pdf';
        fileInput.style.display = 'none';

        fileInput.addEventListener('change', function() {
          if (fileInput.files.length > 0) {
            const newFile = fileInput.files[0];

            if (!confirm('Are you sure you want to replace the existing file?')) {
              return;
            }

            const formData = new FormData();
            formData.append('certificate_file', newFile);
            formData.append('certificate_name', certificateName);
            formData.append('student_id', studentId);
            formData.append('reupload', 'true'); // Indicate reupload request

            fetch('modules/admission/upload_certificate.php', {
                method: 'POST',
                body: formData
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  alert('Certificate uploaded successfully.');

                  // Hide preview section and show the updated certificate table
                  document.getElementById('previewSection').style.display = 'none';
                  document.getElementById('certificateTableSection').style.display = 'block';

                  // Clear preview section
                  document.getElementById('filePreview').innerHTML = '';

                  // Optionally, update the certificate table dynamically by adding the new certificate row
                  const tableBody = document.getElementById('certificatesTable');
                  // Loop through all the rows and find the one for the uploaded certificate
                  const rows = tableBody.getElementsByTagName('tr');
                  for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    const certificateNameCell = cells[0]; // The first cell contains the certificate name

                    // Check if this row is for the uploaded certificate
                    if (certificateNameCell && certificateNameCell.textContent === certificateName) {
                      // Update the row for this certificate
                      cells[1].textContent = 'Uploaded'; // Change upload status
                      cells[2].innerHTML = `
                        <button class="btn btn-save" style="background-color:blue" onclick="viewCertificate('${certificateName}', '${window.studentId}', '${data.file_path}')">View</button>
                        <button class="btn btn-save" onclick="reuploadCertificate('${certificateName}', '${window.studentId}')">Edit</button>
                    `;
                      break; // Exit the loop once the row is updated
                    }
                  }
                } else {
                  alert('Failed to upload certificate. Please try again.');
                }
              })
              .catch(error => {
                console.error('Error reuploading certificate:', error);
                alert('An error occurred. Please try again.');
              });
          }
        });

        document.body.appendChild(fileInput);
        fileInput.click();
        document.body.removeChild(fileInput);
      }
    </script>


  <?php
  }
  ?>



  <?php if (User('PROFILE') == 'student') {


    $userName = user('USERNAME');
    $studentID = GetStudentIdFromUserName($userName);
    // echo "ghryjt" . $studentID;

  ?>

    <!-- <p>Student block is visible</p> -->
    <input type="hidden" id="student_id" name="student_id" value="<?php echo $studentID; ?>">
    <div id="certificateTableSection">
      <div style="max-height: 400px; overflow-y: auto;">
        <table class="table table-bordered table-responsive">
          <thead>
            <tr>
              <th>Certificate Name</th>
              <th>Upload Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="certificatesTable">
            <!-- Dynamically populated rows -->
          </tbody>
        </table>
      </div>
    </div>
    <p class="loading" id="loadingMessage" style="display: none;">Loading certificates...</p>

    <div class="preview-section" id="previewSection">

      <div id="filePreview"></div>
      <div class="style" style="margin-top: 10px;">
        <!-- <div class='dis-flex'> -->

        <button id="confirmBtn" class="btn btn-save" style=" background-color:#080E4B">Confirm</button>
        <button id="cancelBtn" class="btn btn-cancel">Cancel</button>
      </div>
    </div>

    <script>
      //  function fetchStudentDetails() {
      const studentId = document.getElementById('student_id').value;


      fetch(`modules/admission/fetch_certificates.php?student_id=${studentId}`)
        .then(response => response.json())
        .then(certificates => {
          console.log(certificates);
          // document.getElementById('loadingMessage').style.display = 'none';

          // document.getElementById('studentIdForm').style.display = 'none';
          // document.getElementById('certificateTableSection').style.display = 'block';

          const tableBody = document.getElementById('certificatesTable');
          tableBody.innerHTML = '';

          if (certificates.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="3">No certificates found for this student.</td></tr>`;
            return;
          }

          certificates.forEach(cert => {
            let row = `<tr>
          <td>${cert.certificate_name}</td>
          <td>${cert.upload_status}</td>
          <td>`;
            if (cert.upload_status === 'Uploaded') {
              // For uploaded certificates, show a "View" button and a "Reupload" button.
              row += `<button class="btn btn-save" style="background-color:blue" onclick="viewCertificate('${cert.certificate_name}', '${studentId}', '${cert.file_path}')">View</button>
                  <button class="btn btn-save" onclick="reuploadCertificate('${cert.certificate_name}', '${studentId}')">Edit</button>`;
            } else {
              // For certificates not yet uploaded, show file input and upload button.
              row += `<div class="flex-align-center"><input type="file" class="wid-235" id="file_${cert.certificate_name}" onchange="previewFile('${cert.certificate_name}')" />
                  <button class="btn btn-primary m-t-15" onclick="uploadCertificate('${cert.certificate_name}', '${studentId}')">Upload</button></div>`;
            }
            row += `</td></tr>`;
            tableBody.innerHTML += row;
          });
        })
        .catch(error => {
          console.error('Error fetching certificates:', error);
          document.getElementById('loadingMessage').style.display = 'none';
          alert('An error occurred while fetching certificates. Please try again.');
        });


      function viewCertificate(certificateName, studentId, filePath) {


        if (filePath) {
          window.open(filePath, '_blank'); // Opens the file in a new tab
        } else {
          alert('No file available to view for this certificate.');
        }
      }

      function previewFile(certificateName) {
        const fileInput = document.getElementById(`file_${certificateName}`);
        const file = fileInput.files[0];
        const studentId = document.getElementById('student_id').value; // Fetch student ID

        if (file) {
          const previewSection = document.getElementById('previewSection');
          const filePreview = document.getElementById('filePreview');
          filePreview.innerHTML = ''; // Clear previous preview content
          const reader = new FileReader();

          reader.onload = function() {

            if (file.type.startsWith('image/')) {
              const img = document.createElement('img');
              img.src = reader.result;
              img.style.maxWidth = '100%';
              img.style.height = 'auto';
              filePreview.appendChild(img);
            } else if (file.type === 'application/pdf' || file.type === 'application/x-pdf') { // Show PDF preview using Blob URL for more reliable loading
              const pdfBlob = new Blob([reader.result], {
                type: 'application/pdf'
              });
              const pdfUrl = URL.createObjectURL(pdfBlob);
              const iframe = document.createElement('iframe');
              iframe.src = pdfUrl;
              iframe.width = '100%';
              iframe.height = '500px'; // Adjust height as needed
              iframe.style.border = 'none'; // Optional: add styling for the iframe
              filePreview.appendChild(iframe);
            } else {
              alert('Unsupported file type');
            }


            // Hide the certificate table and show the preview section
            document.getElementById('confirmBtn').style.display = 'block';

            document.getElementById('certificateTableSection').style.display = 'none';
            previewSection.style.display = 'block';

            // Store the file, certificate name, and student ID for upload
            window.selectedFile = file;
            window.selectedCertificate = certificateName;
            window.studentId = studentId; // Store the student ID
          };

          // Different methods for handling files based on their type
          if (file.type.startsWith('image/')) {
            reader.readAsDataURL(file); // For images, use Data URL
          } else if (file.type === 'application/pdf') {
            reader.readAsArrayBuffer(file); // For PDF, use ArrayBuffer for better handling
          } else {
            alert('Unsupported file type');
          }
        }
      }

      document.getElementById('confirmBtn').addEventListener('click', function() {
        const formData = new FormData();
        formData.append('certificate_file', window.selectedFile);
        formData.append('certificate_name', window.selectedCertificate);
        formData.append('student_id', window.studentId); // Pass the student ID

        fetch('modules/admission/upload_certificate.php', {
            method: 'POST',
            body: formData,
          })
          .then(response => response.json())
          .then(data => {

            if (data.success) {
              alert('Certificate uploaded successfully.');

              // Hide the preview section and show the certificate table again
              document.getElementById('previewSection').style.display = 'none';
              document.getElementById('certificateTableSection').style.display = 'block';

              // Clear the previous file preview
              document.getElementById('filePreview').innerHTML = '';

              // Get the certificate table body element
              const tableBody = document.getElementById('certificatesTable');

              // Loop through all the rows and find the one for the uploaded certificate
              const rows = tableBody.getElementsByTagName('tr');
              for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                const certificateNameCell = cells[0]; // The first cell contains the certificate name

                // Check if this row is for the uploaded certificate
                if (certificateNameCell && certificateNameCell.textContent === window.selectedCertificate) {
                  // Update the row for this certificate
                  cells[1].textContent = 'Uploaded'; // Change upload status
                  cells[2].innerHTML = `
                        <button class="btn btn-save" style="background-color:blue" onclick="viewCertificate('${window.selectedCertificate}', '${window.studentId}', '${data.file_path}')">View</button>
                        <button class="btn btn-save" onclick="reuploadCertificate('${window.selectedCertificate}', '${window.studentId}')">Edit</button>
                    `;
                  break; // Exit the loop once the row is updated
                }
              }
            } else {
              alert('Failed to upload certificate. Please try again.');
            }
          })
          .catch(error => {
            console.error('Error uploading certificate:', error);
            alert('An error occurred while uploading the certificate. Please try again.');
          });
      });

      document.getElementById('cancelBtn').addEventListener('click', function() {
        document.getElementById('previewSection').style.display = 'none';
        document.getElementById('certificateTableSection').style.display = 'block';
      });


      function reuploadCertificate(certificateName, studentId) {
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*,application/pdf';
        fileInput.style.display = 'none';

        fileInput.addEventListener('change', function() {
          if (fileInput.files.length > 0) {
            const newFile = fileInput.files[0];

            if (!confirm('Are you sure you want to replace the existing file?')) {
              return;
            }

            const formData = new FormData();
            formData.append('certificate_file', newFile);
            formData.append('certificate_name', certificateName);
            formData.append('student_id', studentId);
            formData.append('reupload', 'true'); // Indicate it's a reupload request

            fetch('modules/admission/upload_certificate.php', {
                method: 'POST',
                body: formData
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  alert('Certificate uploaded successfully.');

                  // Hide preview section and show the updated certificate table
                  document.getElementById('previewSection').style.display = 'none';
                  document.getElementById('certificateTableSection').style.display = 'block';

                  // Clear preview section
                  document.getElementById('filePreview').innerHTML = '';

                  // Optionally, update the certificate table dynamically by adding the new certificate row
                  const tableBody = document.getElementById('certificatesTable');
                  // Loop through all the rows and find the one for the uploaded certificate
                  const rows = tableBody.getElementsByTagName('tr');
                  for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    const certificateNameCell = cells[0]; // The first cell contains the certificate name

                    // Check if this row is for the uploaded certificate
                    if (certificateNameCell && certificateNameCell.textContent === certificateName) {
                      // Update the row for this certificate
                      cells[1].textContent = 'Uploaded'; // Change upload status
                      cells[2].innerHTML = `
                        <button class="btn btn-save" style="background-color:blue" onclick="viewCertificate('${certificateName}', '${window.studentId}', '${data.file_path}')">View</button>
                        <button class="btn btn-save" onclick="reuploadCertificate('${certificateName}', '${window.studentId}')">Edit</button>
                    `;
                      break; // Exit the loop once the row is updated
                    }
                  }
                } else {
                  alert('Failed to upload certificate. Please try again.');
                }
              })
              .catch(error => {
                console.error('Error reuploading certificate:', error);
                alert('An error occurred. Please try again.');
              });
          }
        });

        document.body.appendChild(fileInput);
        fileInput.click();
        document.body.removeChild(fileInput);
      }

      // function previewFile(certificateName) {
      //   const fileInput = document.getElementById(`file_${certificateName}`);
      //   const file = fileInput.files[0];

      //   const studentId = document.getElementById('student_id').value; // Fetch student ID

      //   if (file) {
      //     const previewSection = document.getElementById('previewSection');
      //     const filePreview = document.getElementById('filePreview');
      //     const reader = new FileReader();

      //     reader.onload = function() {
      //       filePreview.innerHTML = ''; // Clear previous preview content

      //       if (file.type.startsWith('image/')) {
      //         // Show image preview
      //         const img = document.createElement('img');
      //         img.src = reader.result;
      //         img.onload = function() {
      //           filePreview.appendChild(img);
      //         };
      //         img.onerror = function() {
      //           alert('Error loading image');
      //         };
      //       } else if (file.type === 'application/pdf') {
      //         // Show PDF preview using Blob URL for more reliable loading
      //         const pdfBlob = new Blob([file], {
      //           type: 'application/pdf'
      //         });
      //         const pdfUrl = URL.createObjectURL(pdfBlob);

      //         const iframe = document.createElement('iframe');
      //         iframe.src = pdfUrl;
      //         iframe.width = '100%';
      //         iframe.height = '500px'; // Adjust height as needed
      //         iframe.style.border = 'none'; // Optional: add styling for the iframe
      //         filePreview.appendChild(iframe);
      //       } else {
      //         alert('Unsupported file type');
      //       }

      //       // Hide the certificate table and show the preview section
      //       document.getElementById('certificateTableSection').style.display = 'none';
      //       previewSection.style.display = 'block';

      //       // Store the file, certificate name, and student ID for upload
      //       window.selectedFile = file;
      //       window.selectedCertificate = certificateName;
      //       window.studentId = studentId; // Store the student ID
      //     };

      //     // Different methods for handling files based on their type
      //     if (file.type.startsWith('image/')) {
      //       reader.readAsDataURL(file); // For images, use Data URL
      //     } else if (file.type === 'application/pdf') {
      //       reader.readAsArrayBuffer(file); // For PDF, use ArrayBuffer for better handling
      //     } else {
      //       alert('Unsupported file type');
      //     }
      //   }
      // }

      // document.getElementById('confirmBtn').addEventListener('click', function() {
      //   const formData = new FormData();
      //   formData.append('certificate_file', window.selectedFile);
      //   formData.append('certificate_name', window.selectedCertificate);
      //   formData.append('student_id', window.studentId); // Pass the student ID

      //   fetch('modules/admission/upload_certificate.php', {
      //       method: 'POST',
      //       body: formData,
      //     })
      //     .then(response => response.json())
      //     .then(data => {

      //       if (data.success) {
      //         alert('Certificate uploaded successfully.');
      //         location.reload(); // Reload the page to show the new file

      //       } 

      //       else {
      //         alert('Failed to upload certificate. Please try again.');
      //       }
      //     })
      //     .catch(error => {
      //       console.error('Error uploading certificate:', error);
      //       alert('An error occurred while uploading the certificate. Please try again.');
      //     });
      // });

      // document.getElementById('cancelBtn').addEventListener('click', function() {
      //   document.getElementById('previewSection').style.display = 'none';
      //   document.getElementById('certificateTableSection').style.display = 'block';
      // });
    </script>
  <?php } ?>



</body>

</html>