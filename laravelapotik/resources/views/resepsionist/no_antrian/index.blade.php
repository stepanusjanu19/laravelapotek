<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>cetak no. antrian</title>
  <link rel="stylesheet" type="text/css" href="{{URL::to('css/print.css')}}">
</head>
<body onload="window.print()" class="receipt">
  <section class="sheet padding-10mm">
  <div id="invoice-POS">
    <center id="top">
      <div class="info">
        <h3>No. Antrian Klinik</h3>
        <p>==================</p>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->

    <div id="mid">
      <center>
        <h2>{{$data}}</h2>
        <div>
          <p>==================</p>
          <h5>Terima Kasih.</h5>
        </div>
      </center>
    </div>
</section>

</body>
</html>