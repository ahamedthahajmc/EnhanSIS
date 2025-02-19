<?php

PopTable('header', _addApplication);


$applications = [
    ['id' => 1, 'course' => 'B.sc', 'name' => 'Arsath', 'email' => 'arsath@gamil.com', 'created_at' => '31-12-2024', 'status' => 'pending'],
    ['id' => 2, 'course' => 'BBA', 'name' => 'Fahad', 'email' => 'fahad@gmail.com', 'created_at' => '01-01-2025', 'status' => 'accepted'],
    ['id' => 3, 'course' => 'B.Com', 'name' => 'Rahim', 'email' => 'rahim@gmail.com', 'created_at' => '01-01-2025', 'status' => 'rejected'],

];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Application</title>
    <style>
        .select-right {
            position: absolute;
            right: 30px;
            top: 20px;
        }

        .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="mt-5" id="application">
        <div class="col m-4">
            <div class="row-md-4">

                <select class="btn btn-primary select-right" id="statusSelect" aria-label="Filter by Status" onchange="filterStatus()">
                    <option value="all">All</option>
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div class="row-md-4" style="margin-top:60px">
                <div style="max-height: 400px; overflow-y: auto;">

                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr class="bg-grey-200">
                                <th>S.No</th>
                                <th>Appli.No</th>
                                <th>Course</th>
                                <th>Name</th>
                                <th>Email Id</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $index => $app): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $app['id'] ?></td>
                                    <td><?= $app['course'] ?></td>
                                    <td><?= $app['name'] ?></td>
                                    <td><?= $app['email'] ?></td>
                                    <td><?= $app['created_at'] ?></td>
                                    <td class="status">
                                        <button class="btn btn-<?= ($app['status'] === 'pending' ? 'danger' : ($app['status'] === 'accepted' ? 'success' : 'warning')) ?>">
                                            <?= ucfirst($app['status']) ?>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-success view-btn" style="margin-right:10px" data-id="<?= $app['id'] ?>">View</button>
                                        <?php if($app['status']==='accepted'){?>
                                        <button class="btn btn-primary viewfees-btn" data-id="<?= $app['id'] ?>">View Fees</button><?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
                    <div id="userDetails"></div>
    

    <script>
        
        function filterStatus() {
            const selectedStatus = document.getElementById('statusSelect').value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const statusButton = row.querySelector('.status button');
                const status = statusButton ? statusButton.textContent.trim().toLowerCase() : '';
                
                if (selectedStatus === 'all' || status === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // AJAX function to fetch data for a specific user
        $(document).on('click', '.view-btn', function() {
            var userId = $(this).data('id'); 
            
            $.ajax({
                url: 'modules/admission/viewApplication.php',                
                type: 'POST',
                data: { id: userId }, 
                success: function(response) {
                    
                    $('#userDetails').html(response);
                    $('#viewModal').show(); 
                    $('#application').hide(); 
                   
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); 
                    alert('Error fetching user data.');
                }
            });

        });

        $(document).on('click', '.viewfees-btn', function() {
            var userId = $(this).data('id'); 
            
            $.ajax({
                url: 'modules/admission/viewFees.php',                
                type: 'POST',
                data: { id: userId }, 
                success: function(response) {
                    
                    $('#userDetails').html(response);
                    $('#viewModal').show(); 
                    $('#application').hide(); 
                   
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); 
                    alert('Error fetching user data.');
                }
            });
        });
    </script>
</body>
</html>
