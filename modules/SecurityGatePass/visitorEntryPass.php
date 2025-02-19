<?php
PopTable('header', _securitypass);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Security Gate Pass</title>
</head>

<body>

    <!-- Visitor Entry Form -->
    <div class="form-horizontal m-b-0">

    <h3>Visitor Entry Form</h3>

    <form>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Visitor Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="visitor_name" size="30" placeholder="Visitor Name" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Contact Number</label>
                    <div class="col-lg-8">
                        <input type="text" name="contact_number" size="30" placeholder="Contact Number" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Purpose of Visit</label>
                    <div class="col-lg-8">
                        <input type="text" name="purpose_of_visit" size="30" placeholder="Purpose of Visit" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Time In</label>
                    <div class="col-lg-8">
                        <input type="time" name="time_in" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">ID Proof</label>
                    <div class="col-lg-8">
                        <input type="text" name="id_proof" size="30" placeholder="ID Proof" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Vehicle Number (if any)</label>
                    <div class="col-lg-8">
                        <input type="text" name="vehicle_number" placeholder="Vehicle Number" size="30" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Relationship</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="relationship">
                            <option selected>Choose...</option>
                            <option value="mother">Mother</option>
                            <option value="father">Father</option>
                            <option value="guardian">Guardian</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Pass Type</label>
                    <div class="col-lg-8">
                        <select name="pass_type" class="form-control">
                            <option value="">Select Pass Type</option>
                            <option value="temporary">Temporary</option>
                            <option value="permanent">Permanent</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Visiting Department/Person</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="visiting_department">
                            <option selected>Choose...</option>
                            <option value="bsc_cs">B.Sc CS</option>
                            <option value="bca">BCA</option>
                            <option value="mba">MBA</option>
                        </select>
                    </div>
                </div>
            </div><br>

        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>

    <!-- Gate Pass Records -->
    <hr>
    <br>
    <div class="card">
        <div class="card-header bg-secondary text-white">
            Gate Pass Records
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Std ID</th>
                        <th>Name</th>
                        <th>Purpose</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>John Doe</td>
                        <td>Meeting</td>
                        <td>10:00 AM</td>
                        <td>12:00 PM</td>
                        <td>
                            <button class="btn btn-primary btn-sm">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>Jane Smith</td>
                        <td>Library</td>
                        <td>11:00 AM</td>
                        <td>01:00 PM</td>
                        <td>
                            <button class="btn btn-primary btn-sm">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>

</body>

</html>
