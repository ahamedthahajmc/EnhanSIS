<?php
PopTable('header', _addCalls);
?>
<!DOCTYPE html>
<html lang="en">

<body>
<div class="form-horizontal m-b-0">

    <form method="POST" action="process_call.php">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Caller Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="last" size="30" placeholder="Caller Name" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Call Type</label>
                    <div class="col-lg-8">
                        <select class="form-control" id="call_type" name="call_type" required>
                            <option value="Incoming">Incoming</option>
                            <option value="Outgoing">Outgoing</option>
                        </select>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Receiver Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="first" size="30" placeholder="Receiver Name" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Call Status</label>
                    <div class="col-lg-8">
                        <select class="form-control" id="status" name="status" required>
                            <option value="Completed">Completed</option>
                            <option value="Missed">Missed</option>
                            <option value="Ongoing">Ongoing</option>
                        </select>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Call Duration (in minutes)</label>
                    <div class="col-lg-8">
                        <input type="time" class="form-control" id="call_duration" name="call_duration" min="1" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Call Notes</label>
                    <div class="col-lg-8">
                        <textarea class="form-control" id="call_notes" name="call_notes" rows="4"></textarea>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">Save Call</button>
        </div>

    </form>
</div>
</body>

</html>
