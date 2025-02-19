<?php

PopTable('header', _bankentry);
?>
<div class="mt-4">
    <form>
        <div class="mb-3">
            <label for="bankName" class="form-label">Bank Name</label>
            <input type="text" class="form-control" id="bankName" placeholder="Enter Bank Name">
        </div>
        <div class="mb-3">
            <label for="accountNumber" class="form-label">Account Number</label>
            <input type="text" class="form-control" id="accountNumber" placeholder="Enter Account Number">
        </div>
        <div class="mb-3">
            <label for="transactionDate" class="form-label">Transaction Date</label>
            <input type="date" class="form-control" id="transactionDate">
        </div>
        <button type="submit" class="btn btn-primary">Add Entry</button>
    </form>
</div>
