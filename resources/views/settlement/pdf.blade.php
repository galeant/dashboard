<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <img src="images/logo.png" alt="logo">
    <!-- <div class="jumbotron text-center">
    <h1>My First Bootstrap Page</h1>
    <p>Resize this responsive page to see the effect!</p> 
    </div> -->
    <div class="row" style="background-color:red">
        <div class="col-sm-4">
            <h4>Jumlah Booking Hotel: {{ $data['sum_book_hotel']}}</h4>
        </div>
        <div class="col-sm-4">
            <h4>Jumlah Booking Tour: {{ $data['sum_book_tour']}}</h4>
        </div>
        <div class="col-sm-4">
            <h4>Jumlah Booking Rental Car: {{ $data['sum_book_car']}}</h4>
        </div>
    </div>
</div>

</body>
</html>
