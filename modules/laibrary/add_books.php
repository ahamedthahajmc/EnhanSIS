<?php
PopTable('header', _addBook);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <form>
    <div class="form-horizontal m-b-0">

    <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Book Title</label>
                    <div class="col-lg-8">
                        <input type="text" name="Book Title" size="30" placeholder="Book Title" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Author</label>
                    <div class="col-lg-8">
                    <input type="text" name="Author" size="30" placeholder="Author" class="form-control">
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Genre</label>
                    <div class="col-lg-8">
                        <input type="text" name="Genre" size="30" placeholder="Genre" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Publication Date</label>
                    <div class="col-lg-8">
                    <input type="date" class="form-control" id="publicationDate" class="form-control">
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">ISBN</label>
                    <div class="col-lg-8">
                        <input type="text" name="ISBN" size="30" placeholder="ISBN" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Quantity</label>
                    <div class="col-lg-8">
                    <input type="number" name="quantity" size="30" placeholder="Quantity" class="form-control">
                    </div>
                </div>
            </div>
        </div><br>
        
        <div class="text-right">
            <button type="reset" class="btn btn-secondary">Clear</button>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </div>
    </div>
    </form>
</body>

</html>