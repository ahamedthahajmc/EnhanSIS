<?php 
PopTable('header',_eventView)
?>

<div class="my-5" id="eventList">
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      
      <button class="btn btn-primary" id="addEvent">Add Event</button>
    </div><br>
    <div class="card-body mt-4">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Organizer</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Annual Day</td>
            <td>15 Jan 2025</td>
            <td>Main Hall</td>
            <td>Admin</td>
            <td>
              <button class="btn btn-info btn-sm">View</button>
              <button class="btn btn-warning btn-sm">Edit</button>
              <button class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
          <tr>
            <td>Science Exhibition</td>
            <td>25 Feb 2025</td>
            <td>Lab Block</td>
            <td>Dr. Smith</td><br>
            <td>
              <button class="btn btn-info btn-sm">View</button>
              <button class="btn btn-warning btn-sm">Edit</button>
              <button class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div id="eventCreation"></div>
<script>
$(document).on('click', '#addEvent', function() {

            
            $.ajax({
                url: 'modules/event/eventCreation.php',                
                type: 'POST',
                success: function(response) {
                    
                    $('#eventCreation').html(response);
                    $('#viewModal').show(); 
                    $('#eventList').hide(); 
                   
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); 
                    alert('Error fetching user data.');
                }
            });
        });


</script>