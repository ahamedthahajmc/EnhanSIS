<?php

$applications = [
    ['id' => 1, 'course' => 'B.sc', 'name' => 'Arsath', 'father'=>'mohammed','mother'=>'fathima','mobile'=>'9876543210','dob'=>'20-08-2003','gender'=>'Male','wsnumber'=>'9876543210','altnumber'=>'9812345670','fathernum'=>'9812345670','occupation' => 'Store Keeper' ,'lang'=>'Tamil,English and Arabic','religion'=>'Muslim','add'=>'192,Rob,Street,ThousandLights,Chennai','aadhaar'=>'987654321','passnum'=>'9876543456789','email' => 'arsath@gamil.com', 'created_at' => '31-12-2024', 'status' => 'pending'],
    ['id' => 2, 'course' => 'BBA', 'name' => 'Fahad', 'father'=>'Ferosekhan','mother'=>'Salma','mobile'=>'9681254367','dob'=>'13-03-2003','gender'=>'Male','wsnumber'=>'9876543122','altnumber'=>'9876123400','fathernum'=>'9876123400','occupation' => 'Store Keeper' ,'lang'=>'Tamil and English','religion'=>'Muslim','add'=>'12,middle,Street,Madurai','aadhaar'=>'987654322345','passnum'=>'2345698765', 'email' => 'fahad@gmail.com', 'created_at' => '01-01-2025', 'status' => 'accepted'],
    ['id' => 3, 'course' => 'B.Com', 'name' => 'Rahim', 'father'=>'abdullah','mother'=>'afrin','mobile'=>'9876513411','dob'=>'23-09-2003','gender'=>'Male','wsnumber'=>'9876123432','altnumber'=>'9876543098','fathernum'=>'9876543098','occupation' => 'Employee' ,'lang'=>'Tamil and English','religion'=>'Muslim','add'=>'186,west,Street,Pudukottai','aadhaar'=>'987654329876','passnum'=>'', 'email' => 'rahim@gmail.com', 'created_at' => '01-01-2025', 'status' => 'rejected'],
];


if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    $userDetails = null;


    foreach ($applications as $app) {
        if ($app['id'] == $userId) {
            $userDetails = $app;
            break;
        }
    }
    
    if ($userDetails) {
       
echo "
<div class='panel' style='border: 1px solid #ddd; padding: 20px; background-color: #f9f9f9;'>
 
    <div class='content'>
   
                        <form id='formID'>
                         
                            <div class='row mb-3'>
                                <!-- Left side: Form Fields -->
                                <div class='form-section-header text-primary'>Personal Information</div><hr class='bg-primary'>
                                <div class='col-md-8'>
                                   
                                    <div class='row mb-3'>
                                        <div class='col-md-6 form-group'>
                                            <label for='appName'>Applicant Name</label>
                                            <input type='text' class='form-control' value={$userDetails['name']}  readonly>
                                        </div>
                                        <div class='col-md-6 form-group'>
                                            <label for='fatherName'>Father Name</label>
                                            <input type='text' class='form-control' id='fatherName' name='fatherName' value={$userDetails['father']} readonly>
                                        </div>
                                    </div>
                                    <div class='row mb-3'>
                                        <div class='col-md-6 form-group'>
                                            <label for='mothername'>Mother Name</label>
                                            <input type='text' class='form-control' value={$userDetails['mother']} readonly>
                                        </div>
                                        <div class='col-md-6 form-group'>
                                            <label for='dob'>Date of Birth</label>
                                            <input type='text' class='form-control' id='dob' name='dob' value={$userDetails['dob']} readonly>
                                        </div>
                                    </div>
                                    <div class='row mb-3'>
                                         <div class='col-md-6 form-group'>
                                            <label for='gender'>Gender</label>
                                            <input type='text' class='form-control' id='gender' name='gender' value={$userDetails['gender']} readonly>
                                        </div>
                                       
                                        <div class='col-md-6 form-group'>
                                    <label for='email'>Email Address</label>
                                    <input type='email' class='form-control' id='email' name='email' value={$userDetails['email']} readonly>
                                </div>
                                    </div>
                                </div>
                               <!-- Right side: Profile Image Upload -->
                                <div class='col-md-4 text-center'>
                                    <div class='form-group'>
                                       
                                        <div class='preview-container'>
                                            <img id='previewImage' name='previewImage' src='assets\images\PngItem_5067022.png' width='150px' alt='Preview' />
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>

                            <div class='form-section-header text-primary'>Contact Information</div><hr class='bg-primary'>
                            <div class='row mb-3'>
                                <div class='col-md-3 form-group'>
                                    <label for='mobilenumber'>Mobile Number</label>
                                    <input type='text' class='form-control' id='mobilenumber' name='mobilenumber' value={$userDetails['mobile']} readonly>
                                </div>
                                <div class='col-md-3 form-group'>
                                    <label for='wsnumber'>WhatsApp Number</label>
                                    <input type='text' class='form-control' id='wsnumber'  name='wsnumber' value={$userDetails['wsnumber']} readonly>
                                </div>
                                <div class='col-md-3 form-group'>
                                    <label for='altnumber'>Alternative Mobile Number</label>
                                    <input type='text' class='form-control' id='altnumber' name='altnumber' value={$userDetails['altnumber']} readonly>
                                </div>
                                 <div class='col-md-3 form-group'>
                                    <label for='emergencyperson'>Father/Guardian Mobile No</label>
                                    <input type='text' class='form-control' name='emergencyperson' id='emergencyperson' value={$userDetails['fathernum']} value='' readonly>
                                </div>
                            </div>
                            <div class='row mb-3'>
                               
                                <div class='col-md-3 form-group'>
                                    <label for='fatheroccupation'>Father Occupation</label>
                                    <input type='text' class='form-control' name='fatheroccupation' id='fatheroccupation' value={$userDetails['occupation']} readonly>
                                </div>
                                   <div class='col-md-3 form-group'>
                                    <label for='language'>Language Known</label>
                                    <input type='text' class='form-control' id='language' name='language' value={$userDetails['lang']} readonly>
                                </div>
                                <div class='col-md-3 form-group'>
                                    <label for='religion'>Religion</label>
                                    <input type='text' class='form-control' id='religion' name='religion' value={$userDetails['religion']} readonly>
                                </div>
                                <div class='col-md-3 form-group'>
                                    <label for='address'>Address</label>
                                    <input type='text' class='form-control' id='address' name='address' value={$userDetails['add']} readonly>
                                </div>
                                

                            </div>
                            <br>
                         
                            <div class='row mb-3'>
                                <div class='col-md-6 form-group'>
                                    <label for='aadhaarnumber'>Aadhaar Number</label>
                                    <input type='text' class='form-control' id='aadhaarnumber' name='aadhaarnumber' value={$userDetails['aadhaar']} readonly>
                                </div>
                                <div class='col-md-6 form-group'>
                                    <label for='passportNumber'>Passport Number</label>
                                    <input type='text' class='form-control' id='passportNumber' name='passportNumber' value={$userDetails['passnum']} readonly>
                                </div>

                                </div>

     <div class='form-section-header text-primary'>Education Information</div><hr class='bg-primary'>
                            
                            <div class='row mb-3'>
                            
                                <div class='col-md-6 form-group'>
                                    <label for='nationality'>SSLC Details</label>
                                    <p>Persentage: </p>
                                    <p>School Name:</p>
                                    <p>PassedOut Year:</p>
                                </div>
                                <div class='col-md-6 form-group'>
                                    <label for='currentaddress'>HSC Details</label>
                                     <p>Persentage:</p>
                                    <p>School Name:</p>
                                    <p>PassedOut Year:</p>
                                </div>
                            </div>
                            <br>
                       
                            <div class='row mb-3'>
                                <div class='col-md-6 form-group'>
                                    <label for='degree'>UG Details</label>
                                     <p>Persentage:</p>
                                    <p>College Name:</p>
                                    <p>PassedOut Year:</p>
                                </div>
                                <div class='col-md-6 form-group'>
                                    <label for='course'>PG Details</label>
                                     <p>Persentage:</p>
                                    <p>College Name:</p>
                                    <p>PassedOut Year:</p>
                                </div>
                               
                            </div>
                            <div class='row mb-3'>
                                 <div class='col-md-6 form-group'>
                                    <label for='select'>Selected Course</label>
                                    <input type='text' class='form-control' id='select' name='select' value={$userDetails['course']} readonly>
                                </div>

                            </div>
                            <br>
                          
                        </form>
                    </div>
                </div>
               <div class='row mb-3 align-items-center'>
    <!-- Left Side: Select Box -->
    <div class='col-md-10'>
        <select class='btn btn-primary' id='statusSelect' aria-label='Filter by Status' onchange='filterStatus()'>
            <option value='pending'>Pending</option>
            <option value='accepted'>Accepted</option>
            <option value='rejected'>Rejected</option>
        </select>
    </div>

  
    <div class='col-md-2 text-end'>
        <p class='d-inline me-3 mb-2'><strong>Course:</strong> {$userDetails['course']}</p>
        <p class='d-inline me-3 mb-2'><strong>Available Seat:</strong><span class=' bg-success p-3 ms-2'>10</span></p> 
        <button type='submit' class='btn btn-primary me-2'>Submit</button>
        
    </div>
</div>



";


    } else {
        echo 'User not found.';
    }
}
?>
