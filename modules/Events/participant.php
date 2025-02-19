<?php
PopTable('header', _eventCreate)
?>

<div class="my-5">
  <div class="card">
  <button class="btn btn-primary" id="addparticipant">Add Participant</button>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>John</td>
            <td>john@example.com</td>
            <td>9876543210</td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>
              <button class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
          <tr>
            <td>Jane Smith</td>
            <td>jane@example.com</td>
            <td>9876005410</td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>
              <button class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
$(document).on('click', '#addparticipant', function() {

            
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