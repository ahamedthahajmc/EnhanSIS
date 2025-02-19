<?php

PopTable('header', _vouchers);
?>
<div class="mt-4">

    <form>
        <div class="mb-3">
            <label for="voucherId" class="form-label">Voucher ID</label>
            <input type="text" class="form-control" id="voucherId" placeholder="Enter Voucher ID">
        </div>
        <div class="mb-3">
            <label for="voucherAmount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="voucherAmount" placeholder="Enter Voucher Amount">
        </div>
        <div class="mb-3">
            <label for="voucherDesc" class="form-label">Description</label>
            <textarea class="form-control" id="voucherDesc" rows="3" placeholder="Enter Description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Voucher</button>
    </form>
</div>
