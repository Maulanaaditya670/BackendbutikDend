<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'name', 'kode', 'price', 'size', 'image'
    ];
}
