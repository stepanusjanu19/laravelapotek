<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Tagihan Klinik</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <style type="text/css">
  @page {
    size: 230pt 500pt;
    margin: -10mm 7mm 10mm 7mm;
  }
    body {
            background: #fff;
            background-image: none;
            font-size: 15px;
            margin: 0px;
        }
        img{
          display: block;
          margin:0 40px;
        }
        table th {
            font-size: 9px;
            text-align: left;
        }
        table td{
            font-size: 10px;
        }
        table {
          margin-left: auto;
          margin-right: auto;
          width: 100%
        }
        .table ,th , td {
          border: 1px solid black;
          border-collapse: collapse;
          margin-bottom: 5px;
        }

        .table2 tr td {
          border: none;
        }
  }
  </style>
<body>
  <div style="margin: 0 auto">
        {{-- <img src="photo/toko/{{$toko['photo']}}" width="50" height="50"> --}}
        <br>
        <h4 style="text-align: center;margin-bottom: 1px">
          Klinik
        </h4>
      <hr>
    <table class="table">
            <tr style="font-size: 14px">
              <td>Nama Obat</td>
              <td>Harga</td>
              <td>Qty</td>
              <td>Total</td>
            </tr>
            <?php $no = 1;?>
            @foreach($resep as $data)
              <tr>
                <td>{{ $data['obat']['nama'] }}</td>
                <td>Rp. {{$data['obat']['harga']}}</td>
                <td>{{$data['jumlah']}}</td>
                <td>Rp. {{($data['obat']['harga'] * $data['jumlah'])}}</td>
              </tr>
              @endforeach
              @if($resep[0]['biaya_dokter'] != 0 || $resep[0]['biaya_dokter'] != null)
                  <tr>
                    <td colspan="3">Biaya Dokter</td>
                    <td>Rp. {{number_format($resep[0]['biaya_dokter'])}}</td>
                  </tr>
              @endif
          </table>
  <hr>
      <table class="table2">
              <tr>
                <td>Total:</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right">Rp. {{number_format($tagihan['total'])}}</td>
              </tr>
              <tr>
                <td>Bayar :</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right">Rp. {{number_format($tagihan['bayar'])}}</td>
              </tr>
              <tr>
                <td>Kembalian :</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right">Rp. {{number_format($tagihan['kembalian'])}}</td>
              </tr>
            </table>
        <hr>
          <p style="text-align: center;font-size: 10px">Baca Aturan Pakai</p>
        <table class="table" style="font-size: 6px">
          <tr>
              <td>No</td>
              <td>Nama Obat</td>
              <td>Keterangan</td>
          </tr>
          @foreach($resep as $key => $data)
            <tr>
              <td>{{++$key}}.</td>
              <td>{{$data['obat']['nama']}}</td>
              <td>{{$data['keterangan']}}</td>
            </tr>
          @endforeach
        </table>
              <div style="text-align: center;" class="page-break">
                <b style="font-size: 10px;">Terima Kasih, Semoga Lekas Sembuh</b><br>
              </div>
    <div>
</body>
</html>