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
                                <h3 class="card-title">Edit Soal</h3>
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
                                <form class="frm-soal">
                                    @csrf
                                    <input type="hidden" class="soal-id" name="soal_id" value="{{ $soal->s_id }}">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Paket Soal</label>
                                        <div class="col-sm-9">
                                            <select class="select2 soal-paket w-100" name="soal_paket">
                                                <option value="-" disabled="" selected="">- Pilih Paket -</option>
                                                @foreach($paket as $pk)
                                                <option value="{{ $pk->pk_id }}" @if($soal->s_id_paket == $pk->pk_id) selected @endif>{{ $pk->pk_nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Kategori</label>
                                        <div class="col-sm-9">
                                            <select class="select2 soal-kategori w-100" name="soal_kategori" onchange="pilihKategori()">
                                                <option value="-" disabled="" selected="">- Pilih Kategori -</option>
                                                @foreach($kategori as $kt)
                                                <option value="{{ $kt->kt_id }}" @if($soal->s_id_kategori == $kt->kt_id) selected @endif>{{ $kt->kt_nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Soal</label>
                                        <div class="col-sm-9">
                                            <textarea name="soal" class="summernote soal">{{ $soal->s_pertanyaan }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row container-kunci-single d-none">
                                        <label class="col-sm-3 col-form-label">Kunci Jawaban</label>
                                        <div class="col-sm-9">
                                            <select class="select2 soal-kunci-single w-100" name="soal_kunci_single">
                                                <option value="-" disabled="" selected="">- Pilih Kunci Jawaban -</option>
                                                <option value="1">A</option>
                                                <option value="2">B</option>
                                                <option value="3">C</option>
                                                <option value="4">D</option>
                                                <option value="5">E</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="container-kunci-multiple d-none"></div>
                                    @if($soal->getKategori->kt_tipe_soal == 'single_choice')
                                        <div class="form-group row container-kunci-single">
                                            <label class="col-sm-3 col-form-label">Kunci Jawaban</label>
                                            <div class="col-sm-9">
                                                <select class="select2 soal-kunci-single w-100" name="soal_kunci_single">
                                                    <option value="-" disabled="" selected="">- Pilih Kunci Jawaban -</option>
                                                    <option value="1" @if($soal->getKunci->skj_id_jawaban == "1") selected @endif>A</option>
                                                    <option value="2" @if($soal->getKunci->skj_id_jawaban == "2") selected @endif>B</option>
                                                    <option value="3" @if($soal->getKunci->skj_id_jawaban == "3") selected @endif>C</option>
                                                    <option value="4" @if($soal->getKunci->skj_id_jawaban == "4") selected @endif>D</option>
                                                    <option value="5" @if($soal->getKunci->skj_id_jawaban == "5") selected @endif>E</option>
                                                </select>
                                            </div>
                                        </div>
                                    @elseif($soal->getKategori->kt_tipe_soal == 'multiple_choice')
                                        <div class="container-kunci-multiple">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Kunci Jawaban</label>
                                                <div class="col-lg-9 col-sm-9 col-xs-12 input-group">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default pointer-disable">A.</button>
                                                    </div>
                                                    <select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
                                                        <option value="-" disabled="" selected="">-- Pilih Nilai Jawaban --</option>
                                                        @foreach($soal->getKategori->kt_nilai_benar as $key => $value)
                                                            <option value="{{ $value }}" @if($soal->getKunci->skj_id_jawaban[0]->nilai_jawaban == $value) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="margin-top: -1rem;">
                                                <label class="col-sm-3 col-form-label"></label>
                                                <div class="col-lg-9 col-sm-9 col-xs-12 input-group">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default pointer-disable">B.</button>
                                                    </div>
                                                    <select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
                                                        <option value="-" disabled="" selected="">-- Pilih Nilai Jawaban --</option>
                                                        @foreach($soal->getKategori->kt_nilai_benar as $key => $value)
                                                            <option value="{{ $value }}" @if($soal->getKunci->skj_id_jawaban[1]->nilai_jawaban == $value) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="margin-top: -1rem;">
                                                <label class="col-sm-3 col-form-label"></label>
                                                <div class="col-lg-9 col-sm-9 col-xs-12 input-group">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default pointer-disable">C.</button>
                                                    </div>
                                                    <select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
                                                        <option value="-" disabled="" selected="">-- Pilih Nilai Jawaban --</option>
                                                        @foreach($soal->getKategori->kt_nilai_benar as $key => $value)
                                                            <option value="{{ $value }}" @if($soal->getKunci->skj_id_jawaban[2]->nilai_jawaban == $value) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="margin-top: -1rem;">
                                                <label class="col-sm-3 col-form-label"></label>
                                                <div class="col-lg-9 col-sm-9 col-xs-12 input-group">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default pointer-disable">D.</button>
                                                    </div>
                                                    <select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
                                                        <option value="-" disabled="" selected="">-- Pilih Nilai Jawaban --</option>
                                                        @foreach($soal->getKategori->kt_nilai_benar as $key => $value)
                                                            <option value="{{ $value }}" @if($soal->getKunci->skj_id_jawaban[3]->nilai_jawaban == $value) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="margin-top: -1rem;">
                                                <label class="col-sm-3 col-form-label"></label>
                                                <div class="col-lg-9 col-sm-9 col-xs-12 input-group">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default pointer-disable">E.</button>
                                                    </div>
                                                    <select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
                                                        <option value="-" disabled="" selected="">-- Pilih Nilai Jawaban --</option>
                                                        @foreach($soal->getKategori->kt_nilai_benar as $key => $value)
                                                            <option value="{{ $value }}" @if($soal->getKunci->skj_id_jawaban[4]->nilai_jawaban == $value) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @foreach($soal->getPilihanGanda as $spg)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Jawaban ({{ $spg->sj_abjad }})</label>
                                            <div class="col-sm-9">
                                                <textarea name="soal_jawaban[]" class="form-control summernote soal-jawaban">{{ $spg->sj_jawaban }}</textarea>
                                                <input type="hidden" name="soal_jawaban_abjad[]" class="soal-jawaban-abjad" value="{{ $spg->sj_abjad }}">
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Pembahasan</label>
                                        <div class="col-sm-9">
                                            <textarea name="soal_pembahasan" class="form-control summernote soal-pembahasan">{{ $soal->getKunci->skj_pembahasan }}</textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-right">
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-sm btn-default btn-kembali" onclick="kembali()">Kembali</button>
                                    <button type="button" class="btn btn-sm btn-success btn-update" onclick="updateSoal()">Update</button>
                                </div>
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
        function pilihKategori() {
			let kt_id = $('.soal-kategori').val();

			if (kt_id == null) {
				$('.container-single').addClass('d-none');
            	$('.container-multiple').addClass('d-none');
            	$('.container-kunci-single').addClass('d-none');
				$('.container-kunci-multiple').addClass('d-none');

			} else {
				$.ajax({
	                url: '{{ route("soal.pilih_kategori") }}',
	                type: 'get',
	                data: {
	                	'kt_id': kt_id
	                },
	                success: function(response) {
	                    if (response.status == 'berhasil') {
	                        if (response.data.kt_tipe_soal == 'single_choice') {
	                        	$('.container-kunci-single').empty();
	                        	$('.container-multiple').empty();
	                        	$('.container-kunci-multiple').empty();
	                        	$('.container-single').removeClass('d-none');
	                        	$('.soal-kunci-single').val('-').trigger('change');

	                        } else if (response.data.kt_tipe_soal == 'multiple_choice') {
	                        	$('.container-single').addClass('d-none');
	                        	$('.container-kunci-single').empty();
	                        	$('.container-kunci-multiple').empty();
	                        	$('.container-multiple').removeClass('d-none');
	                        	$('.soal-kunci-multiple').val('-').trigger('change');

	                        	let pilihan_kunci = `<div class="form-group row">
														<label class="col-sm-3 col-form-label">Kunci Jawaban</label>
														<div class="col-lg-9 col-sm-9 col-xs-12 input-group">
															<div class="input-group-btn">
																<button type="button" class="btn btn-default pointer-disable">A.</button>
															</div>
															<select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
															</select>
														</div>
													</div>
													<div class="form-group row" style="margin-top: -1rem;">
                                                        <label class="col-sm-3 col-form-label"></label>
														<div class="col-lg-9 col-sm-9 col-xs-12 input-group">
															<div class="input-group-btn">
																<button type="button" class="btn btn-default pointer-disable">B.</button>
															</div>
															<select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
															</select>
														</div>
													</div>
													<div class="form-group row" style="margin-top: -1rem;">
                                                        <label class="col-sm-3 col-form-label"></label>
														<div class="col-lg-9 col-sm-9 col-xs-12 input-group">
															<div class="input-group-btn">
																<button type="button" class="btn btn-default pointer-disable">C.</button>
															</div>
															<select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
															</select>
														</div>
													</div>
													<div class="form-group row" style="margin-top: -1rem;">
														<label class="col-sm-3 col-form-label"></label>
														<div class="col-lg-9 col-sm-9 col-xs-12 input-group">
															<div class="input-group-btn">
																<button type="button" class="btn btn-default pointer-disable">D.</button>
															</div>
															<select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
															</select>
														</div>
													</div>
													<div class="form-group row" style="margin-top: -1rem;">
														<label class="col-sm-3 col-form-label"></label>
														<div class="col-lg-9 col-sm-9 col-xs-12 input-group">
															<div class="input-group-btn">
																<button type="button" class="btn btn-default pointer-disable">E.</button>
															</div>
															<select class="select2 soal-kunci-multiple" name="soal_kunci_multiple[]">
															</select>
														</div>
													</div>`;

								$('.container-kunci-multiple').append(pilihan_kunci);

								$('.soal-kunci-multiple').empty();
								$('.soal-kunci-multiple').append('<option value="-" disabled="" selected="">- Pilih Nilai Jawaban -</option>');
								$('.soal-kunci-multiple').val('-').trigger('change');
								$.each(response.data.kt_nilai_benar, function(key, val) {
									if (val != null) {
										$('.soal-kunci-multiple').append('<option value="' + val + '">' + val + '</option>');
									}
								})

								$('.select2').select2(); 
	                        }

	                    } else if (response.status == 'gagal') {
	                        Toast.fire({
                                icon: "error",
                                title: "Data kategori gagal diambil"
                            });
	                        console.log(response.message);
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

        function updateSoal() {
			if ($('.soal-paket').val() == '' || $('.soal-paket').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Paket harus dipilih"
                });
            } else if ($('.soal-kategori').val() == '' || $('.soal-kategori').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Kategori harus dipilih"
                });
            } else if ($('.soal').val() == '' || $('.soal').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Soal harus diisi"
                });
            // } else if ($('.soal-kunci').val() == '' || $('.soal-kunci').val() == null) {
            //     iziToastWarning('Peringatan!', 'Kunci jawaban harus dipilih');
            //     setTimeout(function () {
            //         $('.soal-kunci').select2('open');
            //     }, 1000);
            } else if ($('.soal-jawaban').val() == '' || $('.soal-jawaban').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Jawaban harus diisi semua"
                });
            } else if ($('.soal-pembahasan').val() == '' || $('.soal-pembahasan').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Pembahasan harus diisi"
                });
            } else {
                $.ajax({
                    url: '{{ route("soal.update") }}',
                    type: 'post',
                    data: $('.frm-soal').serialize(),
                    success: function(response) {
                        if (response.status == 'berhasil') {
                            Toast.fire({
                                icon: "success",
                                title: "Data berhasil diupdate"
                            })
                            .then(function () {
                                kembali(); 
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

		function kembali() {
			window.location.href = "{{ url('/soal') }}";
		}
    </script>
@endsection