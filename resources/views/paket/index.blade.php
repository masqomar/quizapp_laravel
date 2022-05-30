@extends('layouts.main')
@section('extra_styles')
@endsection

@section('contents')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Paket</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title title-tambah">Tambah Paket</h3>
                                <h3 class="card-title title-edit d-none">Edit Paket</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="frm-paket">
                                    @csrf
                                    <input type="hidden" class="id-paket" name="id_paket">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Paket</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control paket" name="paket" placeholder="Contoh: 1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Waktu</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control waktu" name="waktu" placeholder="jam:menit:detik">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-right">
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-sm btn-success btn-simpan" onclick="storePaket()">Simpan</button>
                                    <button type="button" class="btn btn-sm btn-default btn-batal d-none" onclick="batalPaket()">Batal</button>
                                    <button type="button" class="btn btn-sm btn-success btn-update d-none" onclick="updatePaket()">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List Paket</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped tbl-paket">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Paket</th>
                                            <th class="text-center">Waktu</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('extra_scripts')
    <script>
        var tbl_paket;
        
        $(document).ready(function() {
			$('.waktu').inputmask("99:99:99");

            setTimeout(function() {
				getData();
			}, 1500);
		});

        function reInit() {
			$('.title-tambah').removeClass('d-none');
			$('.title-edit').addClass('d-none');
			$('.id-paket').val('');
			$('.paket').val('');
			$('.paket').focus();
			$('.waktu').val('');
			$('.btn-simpan').removeClass('d-none');
			$('.btn-batal').addClass('d-none');
			$('.btn-update').addClass('d-none');
		}

        function getData() {
			$('.tbl-paket').DataTable().destroy();
			table_paket = $('.tbl-paket').DataTable({
				responsive: true,
				serverSide: true,
				bAutoWidth: false,
				bLengthChange: true,
				bPaginate: true,
				bFilter: true,
				bInfo: true,
				ajax: {
					url: '{{ route("paket.get_data") }}',
					type: "get",
					data: {
						"_token": "{{ csrf_token() }}",
					}
				},
				columns: [{
						data: 'pk_nama',
						name: 'pk_nama'
					},
					{
						data: 'pk_time',
						name: 'pk_time',
						class: 'text-center'
					},
					{
						data: 'action',
						name: 'action',
						class: 'text-center'
					}
				],
				pageLength: 10,
				lengthMenu: [
					[10, 20, 50, -1],
					[10, 20, 50, 'All']
				]
			});
		}

        function storePaket() {
			if ($('.paket').val() == '' || $('.paket').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Paket harus diisi"
                });
            } else if ($('.waktu').val() == '' || $('.waktu').val() == null || $('.waktu').val() == "hh:mm:dd") {
                Toast.fire({
                    icon: "warning",
                    title: "Waktu harus diisi"
                });
            } else {
                $.ajax({
                    url: '{{ route("paket.store") }}',
                    type: 'post',
                    data: $('.frm-paket').serialize(),
                    success: function(response) {
                        if (response.status == 'berhasil') {
                            Toast.fire({
                                icon: "success",
                                title: "Data berhasil disimpan"
                            })
                            .then(function () {
                                reInit();
                                getData();
                            });

                        } else if (response.status == 'gagal') {
                            Toast.fire({
                                icon: "error",
                                title: "Data gagal disimpan"
                            });
                            console.log(response.message)
                        }
                    },
                    error: function(request, status, error) {
                        Toast.fire({
                            icon: "error",
                            title: "Terjadi kesalahan"
                        });
                        console.log(request.responseText);
                    }
                });
            }
		}

		function editPaket(pk_id, pk_nama, pk_waktu) {
			$('.title-tambah').addClass('d-none');
			$('.title-edit').removeClass('d-none');
			$('.id-paket').val(pk_id);
			$('.paket').val(pk_nama);
			$('.paket').focus();
			$('.waktu').val(pk_waktu);
			$('.btn-simpan').addClass('d-none');
			$('.btn-batal').removeClass('d-none');
			$('.btn-update').removeClass('d-none');
		}

		function batalPaket() {
			reInit();
		}

		function updatePaket() {
			if ($('.paket').val() == '' || $('.paket').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Paket harus diisi"
                });
            } else if ($('.waktu').val() == '' || $('.waktu').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Waktu harus diisi"
                });
            } else {
                $.ajax({
                    url: '{{ route("paket.update") }}',
                    type: 'post',
                    data: $('.frm-paket').serialize(),
                    success: function(response) {
                        if (response.status == 'berhasil') {
                            Toast.fire({
                                icon: "success",
                                title: "Data berhasil diupdate"
                            })
                            .then(function () {
                                reInit();
                                getData();
                            });

                        } else if (response.status == 'gagal') {
                            Toast.fire({
                                icon: "error",
                                title: "Data gagal diupdate"
                            });
                            console.log(response.message)
                        }
                    },
                    error: function(request, status, error) {
                        Toast.fire({
                            icon: "error",
                            title: "Terjadi kesalahan"
                        });
                        console.log(request.responseText);
                    }
                });
            }
		}

		function hapusPaket(pk_id) {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah anda yakin ingin menghapus data tersebut ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                // cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
			}).then((result) => {
				// console.log(result);
                if (result.value == true) {
                    $.ajax({
                        url: '{{ route("paket.delete") }}',
                        type: 'post',
                        data: {
                            'pk_id': pk_id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'berhasil') {
                                Toast.fire({
                                    icon: "success",
                                    title: "Data berhasil dihapus"
                                })
                                .then(function () {
                                    getData();
                                });

                            } else if (response.status == 'gagal') {
                                Toast.fire({
                                    icon: "error",
                                    title: "Data gagal dihapus"
                                });
                                console.log(response.message)
                            }
                        },
                        error: function(request, status, error) {
                            Toast.fire({
                                icon: "error",
                                title: "Terjadi kesalahan"
                            });
                            console.log(request.responseText);
                        }
                    });
                }
			});
		}
    </script>
@endsection