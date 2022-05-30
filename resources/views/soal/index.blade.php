@extends('layouts.main')
@section('extra_styles')
@endsection

@section('contents')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Soal</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List Paket</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-sm btn-primary btn-tambah" onclick="createSoal()">Tambah Soal</button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped tbl-soal">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="10%;">Paket</th>
                                            <th class="text-center" width="10%;">Kategori</th>
                                            <th class="text-center">Soal</th>
                                            <th class="text-center" width="15%;">Aksi</th>
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

    @include('soal.modal')
@endsection

@section('extra_scripts')
    <script type="text/html" id="el_jawaban_single">
        <tr class="">
            <td width="1%" align="center" class="abjad"></td>
            <td class="jawaban"></td>
        </tr>
    </script>

    <script type="text/html" id="el_jawaban_mutiple">
        <tr class="">
            <td width="1%" align="center" class="abjad"></td>
            <td class="jawaban"></td>
            <td align="center" class="nilai"></td>
        </tr>
    </script>

    <script>
        var table_soal;

        $(document).ready(function() {
			setTimeout(function() {
				getData();
			}, 1500);
		});

        function getData() {
			$('.tbl-soal').DataTable().destroy();
			table_soal = $('.tbl-soal').DataTable({
				responsive: true,
				serverSide: true,
				bAutoWidth: false,
				bLengthChange: true,
				bPaginate: true,
				bFilter: true,
				bInfo: true,
				ajax: {
					url: '{{ route("soal.get_data") }}',
					type: "get",
					data: {
						"_token": "{{ csrf_token() }}",
					}
				},
				columns: [
					{
						data: 's_id_paket',
						name: 's_id_paket',
						class: 'text-center',
					},
					{
						data: 's_id_kategori',
						name: 's_id_kategori',
						class: 'text-center'
					},
					{
						data: 's_pertanyaan',
						name: 's_pertanyaan'
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

        function createSoal() {
			window.location.href = "{{ url('/soal/create') }}";
		}

        function jawabanSoal(s_id) {
			$.ajax({
		        url: '{{route("soal.jawaban")}}',
		        type: 'get',
		        data: {
		            '_token': "{{ csrf_token() }}",
		            's_id': s_id
		        },
		        success: function(response) {
					console.log(response);
					if (response.status == 'berhasil') {

						if (response.cek.get_kategori.kt_tipe_soal == 'single_choice') {
							let jawaban = '';
							let kunci = response.soal_kunci;

							$('.container-tipe').empty();
							$('.container-tipe').append('<h5> Tipe Soal <b>Single Choice</b></h5>');
							$('.container-kunci').empty();
							$('.tbl-jawaban-multiple').addClass('d-none');
							$('.tbl-jawaban-single').removeClass('d-none');
							$('.tbl-jawaban-single tbody').empty();
	                        
	                        for (let i = 0; i < response.soal_jawaban.length; i++){
								const element = response.soal_jawaban[i];

	                            let el = $('#el_jawaban_single').html();
	                            let dom = $(el);

	                            if (parseInt(element.sj_id) === parseInt(kunci.skj_id_jawaban)){
	                                dom.addClass('font-bold bg-success warna-putih');

	                                $('.container-kunci').append('<h5> Kunci Jawaban <b>' + element.sj_abjad + '</b></h5>');
	                            }

	                            dom.find('.abjad').html(element.sj_abjad);
	                            dom.find('.jawaban').html(element.sj_jawaban);

	                            $('.tbl-jawaban-single tbody').append(dom);
	                        }

						} else if (response.cek.get_kategori.kt_tipe_soal == 'multiple_choice') {
							// console.log(response);
							$('.container-tipe').empty();
							$('.container-tipe').append('<h5> Tipe Soal <b>Multiple Choice</b></h5>');
							$('.container-kunci').empty();
							$('.container-kunci').append('<h5> Jawaban yang paling benar adalah yang memiliki nilai terbesar</h5>');
							$('.tbl-jawaban-single').addClass('d-none');
							$('.tbl-jawaban-multiple').removeClass('d-none');
							$('.tbl-jawaban-multiple tbody').empty();

							for (let i = 0; i < response.soal_jawaban.length; i++){
								const element = response.soal_jawaban[i];
								const value = response.soal_kunci.skj_id_jawaban[i];

	                            let el = $('#el_jawaban_mutiple').html();
	                            let dom = $(el);

	                            dom.find('.abjad').html(element.sj_abjad);
	                            dom.find('.jawaban').html(element.sj_jawaban);
	                            dom.find('.nilai').html(value.nilai_jawaban);

	                            $('.tbl-jawaban-multiple tbody').append(dom);
	                        }
						}

						$('.container-pembahasan').empty();
						$('.container-pembahasan').append(`<div>
																<h5><strong>Pembahasan</strong></h5>
																<p>` + response.soal_kunci.skj_pembahasan + `</p>
															</div>`)

			          	$(".modal-jawaban").modal({
				            backdrop: 'static',
				            keyboard: false
					        });
				        $('.modal-jawaban').modal('show');
					
					} else if (response.status == 'gagal') {
		                iziToastError('Gagal!', 'Terjadi kegagalan');
		                console.log(response.message)
		            }
		        },
		        error: function(request, status, error) {
		            iziToastError('Error!', 'Terjadi kesalahan');
		            console.log(request.responseText);
		        }
		    });
		}

        function editSoal(s_id) {
			window.location.href = "{{url('/soal/edit')}}" + "/" + s_id;
		}

        function hapusSoal(s_id) {
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
                        url: '{{ route("soal.delete") }}',
                        type: 'post',
                        data: {
                            's_id': s_id
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