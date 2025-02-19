<?php
PopTable('header', _borrowBook);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<div class="form-horizontal m-b-0">

    <!-- Search and Filter Section -->

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Search by Book Title</label>
                <div class="col-lg-8">
                    <input type="text" name="searchBook" size="30" id="searchBook" placeholder="Enter book title" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Search by Member Name</label>
                <div class="col-lg-8">
                    <input type="text" name="searchMember" id="searchMember" placeholder="Enter member name" size="30" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Filter by Status</label>
                <div class="col-lg-8">
                    <select class="form-control" id="filterStatus">
                        <option value="all" selected>All</option>
                        <option value="borrowed">Borrowed</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right">
        <button type="reset" class="btn btn-primary">Search</button>
    </div><hr><br>



    <!-- Borrowed Books Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Borrower Name</th>
                    <th>Date Borrowed</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>1984</td>
                    <td>George Orwell</td>
                    <td>John Doe</td>
                    <td>2025-01-01</td>
                    <td>2025-01-15</td>
                    <td><span>Borrowed</span></td>
                    <td class="text-nowrap">
                        <button class="btn btn-sm btn-success">Mark as Returned</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>
</body>

</html>