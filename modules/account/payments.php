<?php

PopTable('header',  _payments);
?>
<div class="mt-4">

    <form>
        <div class="mb-3">
            <label for="payeeName" class="form-label">Payee Name</label>
            <input type="text" class="form-control" id="payeeName" placeholder="Enter Payee Name">
        </div>
        <div class="mb-3">
            <label for="paymentAmount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="paymentAmount" placeholder="Enter Payment Amount">
        </div>
        <div class="mb-3">
            <label for="paymentMode" class="form-label">Payment Mode</label>
            <select class="form-select" id="paymentMode">
                <option>Bank Transfer</option>
                <option>Cheque</option>
                <option>Cash</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Make Payment</button>
    </form>
</div>