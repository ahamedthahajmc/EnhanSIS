<?php
include("../../lang/lang_en.php");
include('../../Data.php');
require_once __DIR__ .'../../../libraries/vendor/autoload.php';
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ensure idval is set, otherwise, handle it gracefully
if (!isset($_GET['idval']) || empty($_GET['idval'])) {
    die("Error: Missing staff ID (idval).");
}

$staffId = $_GET['idval'];
$staffs_qry = 'SELECT * FROM staff WHERE staff_id = :staff_id';
$stmt = $conn->prepare($staffs_qry);
$stmt->execute([':staff_id' => $staffId]); // Use parameterized query to prevent SQL injection
$staff = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the staff

if (!$staff) {
    die("Error: Staff not found.");
}

if (isset($_GET['action']) && $_GET['action'] === 'download') {
    calledthis();
    exit; // Prevent further output
}

function calledthis() {
    $mpdf = new \Mpdf\Mpdf();
    $mpdf -> WriteHTML('
    <style>
        .container {
            margin-top: 20px;
            padding-right: 15px;
            padding-left: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .text-center {
            text-align: center;
        }
        .table-bordered {
            border: 1px solid #ddd;
        }
        .table-cell-pad{        
            padding: 12px 20px;
        }
        .table-responsive {
            width: 100%;
            margin-bottom: 1rem;
            overflow-x: auto;
            -ms-overflow-style: -ms-autohiding-scrollbar;
            border-collapse: collapse;
        }
        .no-border-top {
            border-top: none;
        }
        .row-md-4 {
            margin-top: 60px;
        }
        .bg-grey-200 {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .m-t-20 {
            margin-top: 20px;
        }
        .img-rounded {
            border-radius: 10px;
        }
        h3, h4, h5, h6 {
            margin: 0;
        }
        .text-left{
            text-align: left;        
        }
        .wid225{
            width:225px;
        }
        .font-s-12{
            font-size: 12px;
        }            
    </style>
    
    <div class="container">
    <br/>
    <br/>
        <div class="text-center"><h3>' . _salary_slip . '</h3></div>
        <div class="text-center"><h4>Institution Name</h4></div>
        <div class="text-center"><h5>Address</h5></div>
    
        <div class="row-md-4">
            <table class="table table-responsive border-0">
                <tbody>
                    <tr>
                        <th class="no-border-top text-left">' . _dateofjoining . '</th>
                        <td class="no-border-top text-left">: 28-06-2018</td>
                        <th class="no-border-top text-left">' . _staffname . '</th>
                        <td class="no-border-top text-left">: Ahamed Thaha</td>
                    </tr>
    
                    <tr>
                        <th class="no-border-top text-left">' . _payperiod . '</th>
                        <td class="no-border-top text-left">: August 2021</td>
                        <th class="no-border-top text-left">' . _designation . '</th>
                        <td class="no-border-top text-left">: Assistant Professor</td>
                    </tr>
    
                    <tr>
                        <th class="no-border-top text-left">' . _workeddays . '</th>
                        <td class="no-border-top text-left">: 28</td>
                        <th class="no-border-top text-left">' . _department . '</th>
                        <td class="no-border-top text-left">: Computer Science</td>
                    </tr>
                </tbody>
            </table>
        </div>
    
        <div class="row-md-4">
            <div style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr class="bg-grey-200 text-center">
                            <th class="text-left table-bordered table-cell-pad">' . _earnings . '</th>
                            <th class="text-left table-bordered table-cell-pad">' . _amount . '</th>
                            <th class="text-left table-bordered table-cell-pad">' . _deductions . '</th>
                            <th class="text-left table-bordered table-cell-pad">' . _amount . '</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left table-bordered table-cell-pad">' . _basicpay . '</td>
                            <td class="text-left table-bordered table-cell-pad">10000</td>
                            <td class="text-left table-bordered table-cell-pad">' . _providentfund . '</td>
                            <td class="text-left table-bordered table-cell-pad">1200</td>
                        </tr>
                        <tr>
                            <td class="text-left table-bordered table-cell-pad">' . _incentivepay . '</td>
                            <td class="text-left table-bordered table-cell-pad">1000</td>
                            <td class="text-left table-bordered table-cell-pad">' . _professionaltax . '</td>
                            <td class="text-left table-bordered table-cell-pad">500</td>
                        </tr>
                        <tr>
                            <td class="text-left table-bordered table-cell-pad">' . _houserentallowance . '</td>
                            <td class="text-left table-bordered table-cell-pad">400</td>
                            <td class="text-left table-bordered table-cell-pad">' . _loan . '</td>
                            <td class="text-left table-bordered table-cell-pad">400</td>
                        </tr>
                        <tr>
                            <td class="text-left table-bordered table-cell-pad">' . _mealallowance . '</td>
                            <td class="text-left table-bordered table-cell-pad">200</td>
                            <td class="table-bordered table-cell-pad"></td>
                            <td class="table-bordered table-cell-pad"></td>
                        </tr>
                        <tr>
                            <td class="text-right table-bordered table-cell-pad">' . _totalearnings . '</td>
                            <td class="table-bordered table-cell-pad">11600</td>
                            <td class="text-right table-bordered table-cell-pad">' . _totaldeductions . '</td>
                            <td class="table-bordered table-cell-pad">2100</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right table-bordered table-cell-pad">' . _netpay . '</td>
                            <td class="table-bordered table-cell-pad">9500</td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <h3 class="text-center">9500</h3>
                    <h5 class="text-center">Nine Thousand Five Hundred</h5>
                </div>
            </div>
        </div>
    <br/>
    <br/>
    <br/>
    <br/>
        <div class="row-md-4">
            <table class="table table-responsive">
                <tr>
                    <th class="text-center wid225 no-border-top font-s-12">' . _institutionsealandsignatureofthehead . '</th>
                    <th class="no-border-top"></th>
                    <th class="no-border-top"></th>
                    <th class="text-right no-border-top font-s-12">' . _staffsignature . '</th>
                </tr>
            </table>
            <p class="text-center font-s-12">This is system generated Salary Slip</p>
        </div>
    </div>
    ');
    $mpdf -> Output();

}

// HTML Output
echo '<div class="m-t-20 p-r-15 p-l-15 p-b-15 table-bordered table-cell-pad img-rounded">';
echo '<div class="text-center"><h3>' . _salary_slip . '</h3></div>';
echo '<div class="text-center"><h4>Institution Name</h4></div>';
echo '<div class="text-center"><h5>Address</h5></div>';

echo '<div class="row-md-4" style="margin-top:60px">
<table class="table table-responsive border-0">
                        <tbody>
                            <tr>
                                <th class="no-border-top">' . _dateofjoining . '</th>
                                <th class="no-border-top">: 28-06-2018</th>
                                <th class="no-border-top">' . _staffname . '</th>
                                <th class="no-border-top">: ' . $staff['first_name'] . ' ' . $staff['last_name'] . '</th>
                            </tr>

                            <tr>
                                <th class="no-border-top">' . _payperiod . '</th>
                                <th class="no-border-top">: August 2021</th>
                                <th class="no-border-top">' . _designation . '</th>
                                <th class="no-border-top">: Assistant Professor</th>
                            </tr>

                            <tr>
                                <th class="no-border-top">' . _workeddays . '</th>
                                <th class="no-border-top">: 28</th>
                                <th class="no-border-top">' . _department . '</th>
                                <th class="no-border-top">: Computer Science</th>
                            </tr>
                        </tbody>
                    </table>
</div>';
// Salary Details Table
echo '<div class="row-md-4" style="margin-top:60px">
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-cell-pad table-responsive">
                        <thead>
                            <tr class="bg-grey-200 text-center">
                                <th>' . _earnings . '</th>
                                <th>' . _amount . '</th>
                                <th>' . _deductions . '</th>
                                <th>' . _amount . '</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                                <td>' . _basicpay . '</td>
                                <td>10000</td>
                                <td>' . _providentfund . '</td>
                                <td>1200</td>
                        </tr>
                        <tr>
                                <td>' . _incentivepay . '</td>
                                <td>1000</td>
                                <td>' . _professionaltax . '</td>
                                <td>500</td>
                        </tr>
                        <tr>
                                <td>' . _houserentallowance . '</td>
                                <td>400</td>
                                <td>' . _loan . '</td>
                                <td>400</td>
                        </tr>
                        <tr>
                                <td>' . _mealallowance . '</td>
                                <td>200</td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr>
                        <td class="text-right">' . _totalearnings . '</td>
                                <td>11600</td>
                                <td class="text-right">' . _totaldeductions . '</td>
                                <td>2100</td>
                        </tr>
                        <tr>
                        <td colspan="3" class="text-right">' . _netpay . '</td>
                        <td>9500</td>
                        </tr>
                        </tbody>
                    </table>
                    <div>
                    <h5 class="text-center">9500</h5>
                    <h6 class="text-center">Nine Thousand Five Hundred</h6>
                    </div>
                </div>
            </div>';
echo '<div class="row-md-4" style="margin-top:60px">
<table class="table table-responsive"><tr><th class="text-left no-border-top">' . _institutionsealandsignatureofthehead . '</th><th  class="no-border-top"></th><th  class="no-border-top"></th><th class="text-right no-border-top">' . _staffsignature . '</th></tr></table>
<p class="text-center">This is system generated Salary Slip</p>
</div>';

// Add Download Button with Proper Parameters
echo '<div class="text-right">
        <button id="submit-btn" class="btn btn-success m-t-20" onclick="window.open(\'' . $_SERVER['PHP_SELF'] . '?idval=' . $staffId . '&action=download\', \'_blank\')">
            Download
        </button>
      </div>';

?>
