<?php
PopTable('header', _bill);
?>

<div class="mt-5" id="application">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBillModal">+ Add New Bill</button>
    </div>
    <div class="row-md-4" style="margin-top:60px">
        <div style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr class="bg-grey-200">
                        <th>Bill ID</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example rows -->
                    <tr>
                        <td>101</td>
                        <td>Library Bill</td>
                        <td>₹5,000</td>
                        <td><span class="btn btn-warning">Pending</span></td>
                        <td>
                            <button class="btn btn-sm btn-success" onclick="markAsPaid(101)">Mark as Paid</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteBill(101)">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>102</td>
                        <td>Electricity Bill</td>
                        <td>₹15,000</td>
                        <td><span class="btn btn-success">Paid</span></td>
                        <td>
                            <button class="btn btn-sm btn-success" onclick="markAsPaid(102)">Mark as Paid</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteBill(102)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Bill Modal -->
<div class="modal fade" id="addBillModal" tabindex="-1" aria-labelledby="addBillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBillModalLabel">Add New Bill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addBillForm">
                    <div class="mb-3">
                        <label for="billDescription" class="form-label">Description</label>
                        <input type="text" class="form-control" id="billDescription" placeholder="Enter Bill Description" required>
                    </div>
                    <div class="mb-3">
                        <label for="billAmount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="billAmount" placeholder="Enter Amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="billStatus" class="form-label">Status</label>
                        <select class="form-select" id="billStatus">
                            <option value="Pending" selected>Pending</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Bill</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('addBillForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Fetch form data
        const description = document.getElementById('billDescription').value;
        const amount = document.getElementById('billAmount').value;
        const status = document.getElementById('billStatus').value;

        // Perform AJAX request to save data to the backend
        fetch('add_bill.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    description: description,
                    amount: amount,
                    status: status,
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert('Bill added successfully!');
                    location.reload(); // Reload the page to show updated table
                } else {
                    alert('Failed to add bill. Please try again.');
                }
            })
            .catch((error) => console.error('Error:', error));
    });
</script>