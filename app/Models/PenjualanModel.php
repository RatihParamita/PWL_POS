<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PenjualanModel extends Model
{
    use HasFactory;
    protected $table = 't_penjualan'; // Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'penjualan_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal'];

    public function user():BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id','user_id');
    }

    public function detail()
    {
        return $this->hasMany(PenjualanDetailModel::class, 'penjualan_id', 'penjualan_id');
    }


}