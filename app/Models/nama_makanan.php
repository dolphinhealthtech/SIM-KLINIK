<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nama_makanan extends Model
{
    use HasFactory;
    
    // Ubah nama tabel sesuai dengan tabel yang ada di database
    protected $table = 'nama_makanan'; // tanpa 's' di akhir
    
    protected $fillable = ['nama'];
}




