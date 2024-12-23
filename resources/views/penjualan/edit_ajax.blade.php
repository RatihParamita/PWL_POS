@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan</div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/penjualan/' . $penjualan->penjualan_id.'/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Transaksi Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kasir</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">- Pilih User -</option>
                        @foreach($user as $item)
                            <option {{ ($item->user_id == $penjualan->user_id)? 'selected' : '' }} value="{{ $item->user_id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Transaksi Penjualan</label>
                    <input value="{{ $penjualan->penjualan_kode }}" type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Pembeli</label>
                    <input value="{{ $penjualan->pembeli }}" type="text" name="pembeli" id="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Transaksi Penjualan</label>
                    <input value="{{ $penjualan->penjualan_tanggal }}" type="text" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>
                <div class="row">
                    <div class="col-md-4"><label>Nama Barang</label></div>
                    <div class="col-md-3"><label>Jumlah</label></div>
                    <div class="col-md-3"><label>Harga</label></div>
                    <div class="col-md-2"><label>&nbsp;</label></div>
                </div>
                <div class="form-group">
                    <div id="items-container">
                        @foreach ($penjualan->detail as $index => $detail)
                        <div class="row item-row">
                            <div class="col-md-4">
                                <select name="items[{{ $index }}][barang_id]" class="form-control" required>
                                    <option value="">- Pilih Barang -</option>
                                    @foreach($barang as $item)
                                        <option value="{{ $item->barang_id }}" {{ $item->barang_id == $detail->barang_id ? 'selected' : '' }}>{{ $item->barang_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="items[{{ $index }}][jumlah]" class="form-control jumlah-barang" value="{{ $detail->jumlah }}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="items[{{ $index }}][harga]" class="form-control" value="{{ $detail->harga }}" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-item">Hapus</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-success mt-2" id="add-item">Tambah Barang</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    user_id: {required: true, number: true},
                    penjualan_kode: {required: true, minlength: 3, maxlength: 20},
                    pembeli: {required: true, maxlength: 100},
                    penjualan_tanggal: {required: true, date: true},
                    "items[][jumlah]": { required: true, number: true, min: 1 },
                    "items[][harga]": { required: true, number: true, min: 1 }
                },
                messages: {
                    "items[][jumlah]": {
                        required: "Jumlah barang harus diisi",
                        number: "Jumlah harus berupa angka",
                        min: "Jumlah minimal 1"
                    },
                    "items[][harga]": {
                        required: "Harga harus diisi",
                        number: "Harga harus berupa angka",
                        min: "Harga minimal 1"
                    }
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                            processData: false, // setting processData dan contentType ke false, untuk menghandle file 
                            contentType: false,
                        success: function(response) {
                            if(response.status){
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataPenjualan.ajax.reload();
                            }else{
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-'+prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            let itemIndex = 1; // Deklarasi variabel itemIndex secara global

            // Adding new item row
            $('#add-item').on('click', function() {
                const newItemRow = `
                    <div class="row item-row">
                        <div class="col-md-4">
                            <select name="items[${itemIndex}][barang_id]" class="form-control" required>
                                <option value="">- Pilih Barang -</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="items[${itemIndex}][jumlah]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="items[${itemIndex}][harga]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-item">Hapus</button>
                        </div>
                    </div>
                `;
                $('#items-container').append(newItemRow);
                itemIndex++; 

                $('.remove-item').off('click').on('click', function() {
                    $(this).closest('.item-row').remove();
                });
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
            });
        });
    </script>
@endempty