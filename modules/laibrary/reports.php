<?php
PopTable('header', _report);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
	<form>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label text-right col-lg-4">Select Report Type</label>
					<div class="col-lg-8">
						<select class="form-control" id="reportType" required>
							<option value="" disabled selected>Select a report</option>
							<option value="borrowed">Borrowed Books</option>
							<option value="overdue">Overdue Books</option>
							<option value="inventory">Inventory Status</option>
						</select>
					</div>
				</div>
			</div>
		</div><br>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label text-right col-lg-4">Start Date</label>
					<div class="col-lg-8">
						<input type="date" name="startDate" id="startDate" size="30" class="form-control" required>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label text-right col-lg-4">End Date</label>
					<div class="col-lg-8">
						<input type="date" name="endDate" id="endDate" size="30" class="form-control" required>
					</div>
				</div>
			</div>
		</div><br>

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Generate Report</button>
		</div>
		<hr>
	</form>
	<div class="mt-5">
		<h4 class="text-center">Report Preview</h4>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Book Title</th>
						<th>Author</th>
						<th>Status</th>
						<th>Date Borrowed</th>
						<th>Due Date</th>
					</tr>
				</thead>
				<tbody>
					<!-- Example Row -->
					<tr>
						<td>1</td>
						<td>The Great</td>
						<td>Fahad F</td>
						<td>Borrowed</td>
						<td>01-01-2025</td>
						<td>15-01-2025</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</body>

</html>