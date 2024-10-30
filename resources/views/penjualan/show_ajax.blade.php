@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Terjadi kesalahan!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i>Terjadi kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan!</div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Transaksi Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr><th class="text-right col-4">Kode :</th><td class="col-9">{{$penjualan->penjualan_kode }}</td></tr>
                    <tr><th class="text-right col-4">Nama Pembeli :</th><td class="col-9">{{$penjualan->pembeli }}</td></tr>
                    <tr><th class="text-right col-4">Tanggal :</th><td class="col-9">{{$penjualan->penjualan_tanggal }}</td></tr>
                    <tr><th class="text-right col-4">Kasir :</th><td class="col-9">{{$penjualan->user->nama }}</td></tr>
                </table>
                <h5>Detil Barang:</h5>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th colspan="3" class="text-right">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalHarga = 0; @endphp
                        @foreach ($penjualan->detail as $detail)
                            <tr>
                                <td>{{ $detail->barang->barang_nama }}</td>
                                <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                            </tr>
                            @php $totalHarga += $detail->jumlah * $detail->harga; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>{{ number_format($totalHarga, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
            </div>
        </div>
    </div>
@endempty