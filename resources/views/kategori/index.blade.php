@extends('layouts.main')
@section('extra_styles')
@endsection

@section('contents')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Kategori</h1>
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
                                <h3 class="card-title title-tambah">Tambah Kategori</h3>
                                <h3 class="card-title title-edit d-none">Edit Kategori</h3>
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
                                <form class="frm-kategori">
                                    @csrf
                                    <input type="hidden" class="id-kategori" name="id_kategori">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Kategori</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control kategori" name="kategori" placeholder="Contoh: TKP">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Tipe Soal</label>
                                        <div class="col-sm-9">
                                            <select class="select2 tipe-soal w-100" name="tipe_soal" onchange="pilihTipeSoal()">
                                                <option value="-" disabled="" selected="">-- Pilih Tipe Soal --</option>
                                                <option value="single_choice">Single Choice</option>
                                                <option value="multiple_choice">Multiple Choice</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row single d-none">
                                        <label class="col-sm-3 col-form-label">Nilai Benar</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control benar" name="benar" value="0">
                                            <p class="help-block">jika jawaban benar akan mendapat nilai berapa</p>
                                        </div>
                                    </div>
                                    <div class="form-group row multiple d-none">
                                        <label class="col-sm-3 control-label">Nilai Benar</label>
                                        <div class="col-sm-9">
                                            <div style="display: flex;">
                                                <input type="text" class="form-control text-center benar-multiple" data-mask="9" name="benar_multiple[]" value="0">
                                                <input type="text" class="form-control text-center benar-multiple" data-mask="9" name="benar_multiple[]" value="0">
                                                <input type="text" class="form-control text-center benar-multiple" data-mask="9" name="benar_multiple[]" value="0">
                                                <input type="text" class="form-control text-center benar-multiple" data-mask="9" name="benar_multiple[]" value="0">
                                                <input type="text" class="form-control text-center benar-multiple" data-mask="9" name="benar_multiple[]" value="0">
                                            </div>
                                            <p class="help-block">rentang nilai diisi dari yang terkecil ke terbesar</p>
                                        </div>
                                    </div>
                                    <div class="form-group row salah d-none">
                                        <label for="salah" class="col-sm-3 control-label">Nilai Salah</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control salah" name="salah" value="0">
                                            <p class="help-block">jika jawaban salah akan mendapat nilai berapa</p>
                                        </div>
                                    </div>
                                    <div class="form-group row kosong d-none">
                                        <label for="kosong" class="col-sm-3 control-label">Nilai Kosong</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control kosong" name="kosong" value="0">
                                            <p class="help-block">jika jawaban kosong akan mendapat nilai berapa</p>
                                        </div>
                                    </div>
                                    <div class="form-group row passing-grade d-none">
                                        <label for="passing-grade" class="col-sm-3 control-label">Passing Grade</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control passing-grade" name="passing_grade" value="0">
                                            <p class="help-block">nilai ambang batas kelulusan</p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-right">
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-success btn-simpan" onclick="storeKategori()">Simpan</button>
                                    <button type="button" class="btn btn-default btn-batal d-none" onclick="batalKategori()">Batal</button>
                                    <button type="button" class="btn btn-success btn-update d-none" onclick="updateKategori()">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List Kategori</h3>
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
                                <table class="table table-bordered table-striped tbl-kategori">
                                    <thead>
                                        <tr>
                                            <th width="5%;">No</th>
                                            <th class="text-center">Kategori</th>
                                            <th class="text-center">Tipe</th>
                                            <th class="text-center" width="10%;">Benar</th>
                                            <th class="text-center" width="10%;">Salah</th>
                                            <th class="text-center" width="10%;">Kosong</th>
                                            <th class="text-center" width="15%;">Passing Grade</th>
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
    <script type="text/javascript">
        var table_kategori;

        $(document).ready(function() {
            setTimeout(function() {
                getData();
            }, 1500);
        });

        function pilihTipeSoal() {
            let tipe_soal = $('.tipe-soal').val();
            // console.log(tipe_soal);

            if (tipe_soal == 'single_choice') {
                $('.benar').val('0');
                $('.salah').val('0');
                $('.kosong').val('0');
                $('.passing-grade').val('0');
                $('.multiple').addClass('d-none');
                $('.single').removeClass('d-none');
                $('.salah').removeClass('d-none');
                $('.kosong').removeClass('d-none');
                $('.passing-grade').removeClass('d-none');
            } else if (tipe_soal == 'multiple_choice') {
                $('.benar-multiple').val('0');
                $('.salah').val('0');
                $('.kosong').val('0');
                $('.passing-grade').val('0');
                $('.single').addClass('d-none');
                $('.multiple').removeClass('d-none');
                $('.salah').removeClass('d-none');
                $('.kosong').removeClass('d-none');
                $('.passing-grade').removeClass('d-none');
            } else if (tipe_soal == null) {
                $('.benar').val('0');
                $('.benar-multiple').val('0');
                $('.salah').val('0');
                $('.kosong').val('0');
                $('.passing-grade').val('0');
                $('.single').addClass('d-none');
                $('.multiple').addClass('d-none');
                $('.salah').addClass('d-none');
                $('.kosong').addClass('d-none');
                $('.passing-grade').addClass('d-none');
            }
        }

        function reInit() {
            $('.title-tambah').removeClass('d-none');
            $('.title-edit').addClass('d-none');
            $('.id-kategori').val('');
            $('.kategori').val('');
            $('.kategori').focus();
            $('.tipe-soal').val('-').trigger('change');
            $('.benar').val('0');
            $('.benar-multiple').val('0');
            // $('.benar-terendah').val('');
            // $('.benar-tertinggi').val('');
            $('.salah').val('0');
            $('.kosong').val('0');
            $('.passing-grade').val('0');
            $('.btn-simpan').removeClass('d-none');
            $('.btn-batal').addClass('d-none');
            $('.btn-update').addClass('d-none');
        }

        function getData() {
            $('.tbl-kategori').DataTable().destroy();
            table_kategori = $('.tbl-kategori').DataTable({
                responsive: true,
                serverSide: true,
                bAutoWidth: false,
                bLengthChange: true,
                bPaginate: true,
                bFilter: true,
                bInfo: true,
                ajax: {
                    url: '{{ route("kategori.get_data") }}',
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                    }
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'kt_nama',
                        name: 'kt_nama'
                    },
                    {
                        data: 'kt_tipe_soal',
                        name: 'kt_tipe_soal',
                        class: 'text-center'
                    },
                    {
                        data: 'kt_nilai_benar',
                        name: 'kt_nilai_benar',
                        class: 'text-center'
                    },
                    {
                        data: 'kt_nilai_salah',
                        name: 'kt_nilai_salah',
                        class: 'text-center'
                    },
                    {
                        data: 'kt_nilai_kosong',
                        name: 'kt_nilai_kosong',
                        class: 'text-center'
                    },
                    {
                        data: 'kt_passing_grade',
                        name: 'kt_passing_grade',
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

        function storeKategori() {
            if ($('.kategori').val() == '' || $('.kategori').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "kategori harus diisi"
                });
            // } else if ($('.tipe-soal').val() == '' || $('.tipe-soal').val() == null) {
            //     iziToastWarning('Peringatan!', 'Tipe soal harus dipilih');
            //     setTimeout(function () {
            //         $('.tipe-soal').select2('open');
            //     }, 1000);
            // } else if ($('.benar').val() == '' || $('.benar').val() == null) {
            //     iziToastWarning('Peringatan!', 'Nilai benar harus diisi');
            //     setTimeout(function () {
            //         $('.benar').focus();
            //     }, 1000);
            // } else if ($('.salah').val() == '' || $('.salah').val() == null) {
            //     iziToastWarning('Peringatan!', 'Nilai salah harus diisi');
            //     setTimeout(function () {
            //         $('.salah').focus();
            //     }, 1000);
            // } else if ($('.kosong').val() == '' || $('.kosong').val() == null) {
            //     iziToastWarning('Peringatan!', 'Nilai kosong harus diisi');
            //     setTimeout(function () {
            //         $('.kosong').focus();
            //     }, 1000);
            } else {
                $.ajax({
                    url: '{{ route("kategori.store") }}',
                    type: 'post',
                    data: $('.frm-kategori').serialize(),
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

        function editKategori(kt_id, kt_nama, kt_tipe_soal, kt_nilai_benar, kt_nilai_salah, kt_nilai_kosong, kt_passing_grade) {
            $('.title-tambah').addClass('d-none');
            $('.title-edit').removeClass('d-none');
            $('.id-kategori').val(kt_id);
            $('.kategori').val(kt_nama);
            $('.kategori').focus();
            $('.tipe-soal').val(kt_tipe_soal).trigger('change');
            $('.benar').val(kt_nilai_benar);
            $('.salah').val(kt_nilai_salah);
            $('.kosong').val(kt_nilai_kosong);
            $('.passing-grade').val(kt_passing_grade);
            $('.btn-simpan').addClass('d-none');
            $('.btn-batal').removeClass('d-none');
            $('.btn-update').removeClass('d-none');
        }

        function editKategoriMultiple(kt_id, kt_nama, kt_tipe_soal, kt_rentang1, kt_rentang2, kt_rentang3, kt_rentang4, kt_rentang5, kt_nilai_salah, kt_nilai_kosong, kt_passing_grade) {
            $('.title-tambah').addClass('d-none');
            $('.title-edit').removeClass('d-none');
            $('.id-kategori').val(kt_id);
            $('.kategori').val(kt_nama);
            $('.kategori').focus();
            $('.tipe-soal').val(kt_tipe_soal).trigger('change');
            $('.benar-multiple').eq(0).val(kt_rentang1);
            $('.benar-multiple').eq(1).val(kt_rentang2);
            $('.benar-multiple').eq(2).val(kt_rentang3);
            $('.benar-multiple').eq(3).val(kt_rentang4);
            $('.benar-multiple').eq(4).val(kt_rentang5);
            $('.salah').val(kt_nilai_salah);
            $('.kosong').val(kt_nilai_kosong);
            $('.passing-grade').val(kt_passing_grade);
            $('.btn-simpan').addClass('d-none');
            $('.btn-batal').removeClass('d-none');
            $('.btn-update').removeClass('d-none');
        }

        function batalKategori() {
            reInit();
        }

        function updateKategori() {
            if ($('.kategori').val() == '' || $('.kategori').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Kategori harus diisi"
                });
            } else if ($('.tipe-soal').val() == '' || $('.tipe-soal').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Tipe soal harus dipilih"
                });
            // } else if ($('.tipe-soal').val() == 'single_choice') {
            // 	if ($('.benar').val() == '' || $('.benar').val() == null) {
            //      iziToastWarning('Peringatan!', 'Nilai benar harus diisi');
            //      setTimeout(function () {
            //          $('.benar').focus();
            //      }, 1000);
            //  }
            // } else if ($('.tipe-soal').val() == 'multiple_choice') {
            // 	if ($('.benar-terendah').val() == '' || $('.benar-terendah').val() == null) {
            //      iziToastWarning('Peringatan!', 'Nilai benar terendah harus diisi');
            //      setTimeout(function () {
            //          $('.benar-terendah').focus();
            //      }, 1000);
            //  } else if ($('.benar-tertinggi').val() == '' || $('.benar-tertinggi').val() == null) {
            //      iziToastWarning('Peringatan!', 'Nilai benar tertinggi harus diisi');
            //      setTimeout(function () {
            //          $('.benar-tertinggi').focus();
            //      }, 1000);
            //  }
            } else if ($('.salah').val() == '' || $('.salah').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Nilai salah harus diisi"
                });
            } else if ($('.kosong').val() == '' || $('.kosong').val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Nilai kosong harus diisi"
                });
            } else {
                $.ajax({
                    url: '{{ route("kategori.update") }}',
                    type: 'post',
                    data: $('.frm-kategori').serialize(),
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

        function hapusKategori(kt_id) {
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
                        url: '{{ route("kategori.delete") }}',
                        type: 'post',
                        data: {
                            'kt_id': kt_id
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