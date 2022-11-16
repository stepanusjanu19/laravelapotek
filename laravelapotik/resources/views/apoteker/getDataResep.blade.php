@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="x_panel">
		<div class="x_title">
			<h2>Isi Resep | <span class="btn btn-primary btn-flat" style="color:white">Dr. {{$nama_dokter['dokter']['nama']}}</span> | <span class="btn btn-info btn-flat" style="color:white">Pasien: {{$nama_pasien['pasien']['nama']}}</span></h2>
			<ul class="nav navbar-right panel_toolbox">
				<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
			</li>
			<li><a class="close-link"><i class="fa fa-close"></i></a>
		</li>
	</ul>
	<div class="clearfix"></div>
</div>
<div class="x_content">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<a href="{{route('apoteker.index')}}" class="btn btn-danger btn-flat btn-md"><i class="fa fa-arrow-left"></i> Kembali</a>
		@if ($ada)
			<button class="btn btn-primary btn-flat btn-md btn-konfirmasi pull-right">Konfirmasi pembayaran <i class="fa fa-credit-card"></i></button>
		@endif
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div style="margin-top: 5px;text-align: center;">
			<h4>Deskripsi Pembayaran</h4>
		</div>
		<hr>
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<th>Jumlah Bayar</th>
					<td>
						<input id="bayar" type="number" name="bayar" min="0" value="0" class="form-control">
					</td>
				</tr>
				<tr>
					<th>Jumlah Obat</th>
					<td id="total-jumlah-ada"></td>
				</tr>
				<tr>
					<th>Biaya Dokter</th>
					<td>Rp. {{($ada ? $ada[0]['biaya_dokter'] : $habis[0]['biaya_dokter'])}}</td>
					<input type="hidden" value="{{($ada ? $ada[0]['biaya_dokter'] : $habis[0]['biaya_dokter'])}}" id="biaya_dokter">
				</tr>
				<tr>
					<th>Biaya Obat</th>
					<td id="total-harga-ada"></td>
				</tr>
				<tr>
					<th>Total Keseluruhan</th>
					<td id="total-keseluruhan"></td>
				</tr>
				<tr>
					<th>Uang Kembalian</th>
					<td id="kembalian"></td>
				</tr>
			</tbody>
		</table>
		<div style="text-align: center;">
			<h4>Daftar Obat</h4>
		</div>
		<hr>
		<div class="table-responsive">
		<table  class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama Obat</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Stok Obat</th>
				</tr>
			</thead>
			@if ($ada)
			<tbody>
				<?php $no = 1;?>
				@foreach ($ada as $data)
				<tr class="baris">
					<td>{{$no++}}</td>
					<td>{{$data['obat']['nama']}} <input type="hidden" name="id[]" class="id" value="{{$data['id']}}"><input type="hidden" name="pasien_id" value="{{$data['pasien_id']}}"></td>
					<td class="harga-ada">{{$data['obat']['harga']}}</td>
					<td class="jumlah-ada">{{$data['jumlah']}}</td>
					<td>
						<span class="btn btn-flat btn-sm btn-success btn-block">Tersedia <i class="fa fa-check"></i></span>
					</td>
				</tr>
				@endforeach
			</tbody>
			@else
			<tbody>
				<tr>
					<td colspan="5" style="text-align: center;">Tidak ada obat tersedia</td>
				</tr>
			</tbody>
			@endif

		</table>
		</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<table id="habis" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama Obat</th>
					<th>Stok Obat </th>
				</tr>
			</thead>
			<tbody>
				<?php $no = 1;?>
				@if($habis == null)
					<tr class="baris">
						<td colspan="3" style="text-align: center;">Tidak ada obat yang tidak tersedia</td>
					</tr>
				@endif
				@foreach ($habis as $data)
				<tr class="baris">
					<td>{{$no++}}</td>
					<td>{{$data['obat']['nama']}}</td>
					<td>
						<span class="btn btn-flat btn-sm btn-danger btn-block">Habis <i class="fa fa-close"></i></span>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="row">
		<div class="col-xs-12 ">
			@if($habis)
		<form action="{{url('/apoteker/DetailResep/dokter_id='.$data['dokter_id']. '&pasien_id='.$data['pasien_id'].'/Print')}}" method="post" target="_blank">
		{{csrf_field()}}
		@foreach ($habis as $data)
		<input type="hidden" name="habis[]" value="{{$data['id']}}">
		@endforeach
			<button type="submit" class="btn btn-warning btn-flat btn-md pull-right btn-print">Print Obat yang tidak tersedia <i class="fa fa-print"></i></button>
		</form>
		@endif
		</div>
	</div>
</div>
</div>
</div>
</div>
@endsection
@section('customJs')
<script type="text/javascript">
	$(document).ready(function() {
		var sum = 0;
		var quantity = 0;
		$('.harga-ada').each(function() {
			var price = $(this);
			var q = price.closest('tr').find('.jumlah-ada').text();
			sum += parseInt(price.text()) * parseInt(q);
			quantity += parseInt(q);
		});
		$('#total-harga-ada').text('Rp. '+sum);
		$('#total-jumlah-ada').text(quantity+ ' Obat');

		// pembayaran
		var biaya_dokter = $('#biaya_dokter').val();
		var total_sementara = parseFloat(sum) + parseFloat(biaya_dokter);
		$('#total-keseluruhan').text('Rp. '+total_sementara)
		var kembalian;
		$('#bayar').on('keyup', function() {
			var bayar = $(this).val();
			kembalian = bayar-total_sementara;

			$('#kembalian').text('Rp. '+kembalian)
		});

		// Process it
		$('.btn-konfirmasi').on('click', function(e) {
			e.preventDefault();
			let id = $('input[name="id[]"').serializeArray();
			let pasien_id = $('input[name="pasien_id"]').val();
			let dokter_id = '{{$dokter_id}}';
			let tgl_resep = '{{($ada ? $ada[0]['created_at'] : $habis[0]['created_at'])}}';
			let biaya_dokter = $('#biaya_dokter').val();
			bayar = $('#bayar').val();
			let data = {
				id:id,
				pasien_id:pasien_id,
				total: total_sementara,
				bayar: bayar,
				tgl_resep: tgl_resep,
				kembalian: kembalian,
				biaya_dokter: biaya_dokter
			};
			$.ajax({
			    url: '{{route('postResep')}}',
			    data: data,
			    method:'POST',
			    dataType: 'json',
				async:false
		  	}).done(function(data) {
		  		toastr.success('Success !', 'Pembayaran sudah dikonfirmasi !');
				$('.btn-konfirmasi').prop('disabled', true);
				newTabs(data.id)
		  	});

			function newTabs(tagihan_id) {
				window.open("/apoteker/print-tagihan/tagihan="+tagihan_id+"&dokter_id="+dokter_id+"&pasien_id="+pasien_id, "_newtab")
			}

		});
	});
</script>
@endsection