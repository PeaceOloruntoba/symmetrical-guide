<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCertificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'certificate_path',
        'name',
    ];

    /**
     * Get the product that owns the certificate.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}