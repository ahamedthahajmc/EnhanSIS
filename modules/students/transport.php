<?php
PopTable('header', _transport);
include('../../Data.php');

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query("SELECT * FROM bus_transport_fees ORDER BY id DESC");
    $transport_fees_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error retrieving data: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Transport Fees</title>
</head>

<body>


    <div class="text-right mb-3">
        <button id="addNew" class="btn btn-primary">Add New</button>
    </div>
    <br>

    <table id="transportFeesTable" class="table table-bordered">
        <thead>
            <tr class="bg-grey-200">
                <th>Bus Route</th>
                <th>Bus No</th>
                <th>Transport Fees</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transport_fees_data as $row): ?>
                <tr data-id="<?php echo $row['id']; ?>">
                    <td><?php echo htmlspecialchars($row['place']); ?></td>
                    <td><?php echo htmlspecialchars($row['bus_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['transport_fees']); ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm editBtn">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Form for Add/Edit -->
    <div id="formContainer" style="display: none;">
        <h3 id="formTitle">Add Transport Fees</h3>

        <form id="transportFeesForm">
            <input type="hidden" id="recordId" name="id" value="">

            <div class="form-group">
                <label for="place">Place:</label>
                <input type="text" class="form-control" id="place" name="place" required>
            </div>
            <div class="form-group">
                <label for="bus_no">Bus No:</label>
                <input type="text" class="form-control" id="bus_no" name="bus_no" required>
            </div>
            <div class="form-group">
                <label for="transport_fees">Transport Fees:</label>
                <input type="number" class="form-control" id="transport_fees" name="transport_fees" min="0" required>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary" id="submitBtn">Add</button>
                <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
            </div>
        </form>
        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#addNew').on('click', function() {
                $('#formContainer').show();
                $('#transportFeesTable').hide();
                $('#addNew').hide();
                $('#formTitle').text('Add Bus Route & Transport Fees');
                $('#submitBtn').text('Add');
                $('#recordId').val('');
                $('#place').val('');
                $('#bus_no').val('');
                $('#transport_fees').val('');
                $('#errorMessages').hide();
            });

            $('#transportFeesForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: 'modules/students/AddTransportFees.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);

                            // Hide the form & show the table again
                            $('#formContainer').hide();
                            $('#transportFeesTable').show();
                            $('#addNew').show();

                            // Dynamically update the table without reloading the page
                            let newRow = `
                            <tr data-id="${response.new_id}">
                                <td>${$('#place').val()}</td>
                                <td>${$('#bus_no').val()}</td>
                                <td>${$('#transport_fees').val()}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm editBtn">Edit</button>
                                </td>
                            </tr>
                `;

                            if ($('#recordId').val() === '') {
                                // If adding a new record, append it to the table
                                $('#transportFeesTable tbody').prepend(newRow);
                            } else {
                                // If updating an existing record, update that row
                                let row = $('tr[data-id="' + $('#recordId').val() + '"]');
                                row.find('td').eq(0).text($('#place').val());
                                row.find('td').eq(1).text($('#bus_no').val());
                                row.find('td').eq(2).text($('#transport_fees').val());
                            }

                            // Clear the form fields
                            $('#recordId').val('');
                            $('#place').val('');
                            $('#bus_no').val('');
                            $('#transport_fees').val('');
                        } else {
                            $('#errorMessages').show().html(response.errors ? response.errors.join('<br>') : response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#errorMessages').show().text('Error: Unable to process request.');
                    }
                });
            });


            $('#transportFeesTable').on('click', '.editBtn', function() {
                const row = $(this).closest('tr');
                $('#recordId').val(row.data('id'));
                $('#place').val(row.find('td').eq(0).text());
                $('#bus_no').val(row.find('td').eq(1).text());
                $('#transport_fees').val(row.find('td').eq(2).text());
                $('#formTitle').text('Edit Bus Route & Transport Fees');
                $('#submitBtn').text('Update');
                $('#formContainer').show();
                $('#transportFeesTable').hide();
                $('#addNew').hide();
                $('#errorMessages').hide();
            });

            $('#cancelBtn').on('click', function() {
                $('#formContainer').hide(); // Hide the form
                $('#transportFeesTable').show(); // Show the table
                $('#addNew').show(); // Show the "Add New" button
            });

            $('#submit').on('click', function() {
                location.reload();
            });
        });
    </script>

</body>

</html>