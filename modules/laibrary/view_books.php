<?php
PopTable('header', _viewBook);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .table-highlight tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
    </style>
</head>

<body>
<div class="form-horizontal m-b-0">

        <!-- Search Bar -->
        <div class="mb-4">
            <input type="text" id="searchBar" class="form-control" placeholder="Search for a book by name...">
        </div>

        <!-- Book Table -->
        <table class="table table-bordered table-striped table-highlight">
            <thead class="table-dark">
                <tr>
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody id="bookTable">
                <tr data-bs-toggle="modal" data-bs-target="#bookDetailsModal" onclick="showBookDetails('001', 'Book A', 'Author A', 'Fiction', '500')">
                    <td>001</td>
                    <td>Book A</td>
                    <td>Fiction</td>
                </tr>
                <tr data-bs-toggle="modal" data-bs-target="#bookDetailsModal" onclick="showBookDetails('002', 'Book B', 'Author B', 'Non-Fiction', '450')">
                    <td>002</td>
                    <td>Book B</td>
                    <td>Non-Fiction</td>
                </tr>
                <tr data-bs-toggle="modal" data-bs-target="#bookDetailsModal" onclick="showBookDetails('003', 'Book C', 'Author C', 'Mystery', '300')">
                    <td>003</td>
                    <td>Book C</td>
                    <td>Mystery</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Book Details Modal -->
    <div class="modal fade" id="bookDetailsModal" tabindex="-1" aria-labelledby="bookDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookDetailsModalLabel">Book Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Book ID:</strong> <span id="modalBookID"></span></p>
                    <p><strong>Book Name:</strong> <span id="modalBookName"></span></p>
                    <p><strong>Author:</strong> <span id="modalAuthor"></span></p>
                    <p><strong>Category:</strong> <span id="modalCategory"></span></p>
                    <p><strong>Price:</strong> $<span id="modalPrice"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>

    <script>
        // Book search functionality
        document.getElementById('searchBar').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#bookTable tr');
            rows.forEach(row => {
                let bookName = row.cells[1].textContent.toLowerCase();
                row.style.display = bookName.includes(filter) ? '' : 'none';
            });
        });

        // Show book details in modal
        function showBookDetails(id, name, author, category, price) {
            document.getElementById('modalBookID').textContent = id;
            document.getElementById('modalBookName').textContent = name;
            document.getElementById('modalAuthor').textContent = author;
            document.getElementById('modalCategory').textContent = category;
            document.getElementById('modalPrice').textContent = price;
        }
    </script>
    </div>
</body>

</html>
