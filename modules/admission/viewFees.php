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
       
echo "<div class='panel' style='border: 1px solid #ddd; padding: 20px; background-color: #f9f9f9; padding: 20px;'>
    <div class='content'>
        <h1 class='text-primary'>Fees Details</h1>
        <div style='margin-top: 20px;'>
        <label for='fees-amount' style='font-weight: bold;'>Fees Type:</label>
        <span id='fees-amount' style='font-weight: bold;'>Admission Fees</span><br>    
        <label for='fees-amount' style='font-weight: bold;'>Fees Amount:</label>
            <span id='fees-amount' style='font-weight: bold;'>2000</span><br>
            <label for='fees-status' style='font-weight: bold;'>Fees Status:</label>
            <span id='fees-status' style='color: red; font-weight: bold;'>Pending</span>
        </div></div></div>
    <button id='change-status-btn' class='btn btn-primary' style='margin-top: 20px;'>Pay Fees</button>

        
        <!-- Fee Details Table -->
        <div id='admin-ui' style='margin-top: 20px; display: none;'>
            <table class='table table-bordered' id='fee_type'>
                <thead>
                    <tr class='bg-grey-200'>
                        <th>Fee Type</th>
                        <th>Balance Amount</th>
                        <th>Amount to Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td value='book_fees'>Admission Fees</td>
                        <td id='balance-amount'>2000</td>
                        <td><input class='form-control' id='bookpay_amount' placeholder='Enter amount' type='number'></td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <div class='text-right'>
                <button class='btn btn-success' id='submit-payment-btn'>Pay</button>
            </div>
        </div>
  


<script>
    const changeStatusBtn = document.getElementById('change-status-btn');
    const adminUI = document.getElementById('admin-ui');
    const submitPaymentBtn = document.getElementById('submit-payment-btn');
    const feesStatusSpan = document.getElementById('fees-status');
    const feesAmountSpan = document.getElementById('fees-amount');
    const balanceAmount = document.getElementById('balance-amount');
    const bookPayAmount = document.getElementById('bookpay_amount');

    // Show payment UI on button click
    changeStatusBtn.addEventListener('click', () => {
        adminUI.style.display = 'block';
        changeStatusBtn.style.display = 'none';
    });

    // Handle payment submission
    submitPaymentBtn.addEventListener('click', () => {
        const paymentAmount = parseFloat(bookPayAmount.value);
        const balance = parseFloat(balanceAmount.textContent);

        if (!paymentAmount || paymentAmount <= 0) {
            alert('Please enter a valid amount to pay.');
            return;
        }

        if (paymentAmount > balance) {
            alert('Payment amount exceeds the balance amount!');
            return;
        }

        // Update balance and status
        const newBalance = balance - paymentAmount;
        balanceAmount.textContent = newBalance;

        if (newBalance === 0) {
            feesStatusSpan.textContent = 'Paid';
            feesStatusSpan.style.color = 'green';
        } else {
            feesStatusSpan.textContent = 'Partially Paid';
            feesStatusSpan.style.color = 'orange';
        }

        // Optionally hide admin UI after submission
        adminUI.style.display = 'none';
        changeStatusBtn.style.display = 'block';
        bookPayAmount.value = ''; // Clear input field
    });
</script>

";


    } else {
        echo 'User not found.';
    }
}
?>
