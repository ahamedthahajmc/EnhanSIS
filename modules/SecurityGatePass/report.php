<?php
PopTable('header', _report);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Security Gate Pass Report</title>
</head>

<body>
  <form id="filterForm" class="mb-4">

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label text-right col-lg-4">From Date</label>
          <div class="col-lg-8">
            <input type="date" name="From Date" size="30" class="form-control">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label text-right col-lg-4">To Date</label>
          <div class="col-lg-8">
            <input type="date" name="To Date" size="30" class="form-control">
          </div>
        </div>
      </div>
    </div><br>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label text-right col-lg-4">Gate Pass ID</label>
          <div class="col-lg-8">
            <input type="text" name="gatePassId" size="30" class="form-control" placeholder="Enter Gate Pass ID">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label text-right col-lg-4">Student Name</label>
          <div class="col-lg-8">
            <input type="text" name="Student_name" size="30" class="form-control" placeholder="Student Name">
          </div>
        </div>
      </div>
    </div><br>

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label text-right col-lg-4">Department</label>
          <div class="col-lg-8">
            <select id="department" class="form-control">
              <option value="">Select Department</option>
              <option value="IT">IT</option>
              <option value="BCA">BCA</option>
              <option value="B.Sc">B.Sc</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label text-right col-lg-4">Pass Type</label>
          <div class="col-lg-8">
            <select id="passType" class="form-control">
              <option value="">Select Pass Type</option>
              <option value="Temporary">Temporary</option>
              <option value="Permanent">Permanent</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label text-right col-lg-4">Purpose</label>
          <div class="col-lg-8">
            <select id="purpose" class="form-control">
              <option value="">Select Purpose</option>
              <option value="Visitor">Visitor</option>
              <option value="Delivery">Delivery</option>
            </select>
          </div>
        </div>
      </div>
    </div>
<br>
    <div class="text-right">
      <button type="button" class="btn btn-primary w-100">Search</button>
      <button type="reset" class="btn btn-secondary w-100">Reset</button>
    </div>

  </form>
  <hr>

  <!-- Report Table -->
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Gate Pass ID</th>
          <th>Name</th>
          <th>Department</th>
          <th>Pass Type</th>
          <th>Purpose</th>
          <th>Issued Date</th>
          <th>Expiry Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Example row -->
        <tr>
          <td>GP001</td>
          <td>John Doe</td>
          <td>IT</td>
          <td>Temporary</td>
          <td>Visitor</td>
          <td>2025-01-10</td>
          <td>2025-01-15</td>
          <td><span>Valid</span></td>
          <td class="text-nowrap">
            <button class="btn btn-sm btn-info">View</button>
            <button class="btn btn-sm btn-primary">Print</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</body>

</html>