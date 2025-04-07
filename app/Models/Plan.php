<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'currency',
        'billing_period',
        'has_chat',
        'has_company_list',
        'has_product_page',
        'has_wallet_system',
        'is_active',
        'is_popular',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'has_chat' => 'boolean',
        'has_company_list' => 'boolean',
        'has_product_page' => 'boolean',
        'has_wallet_system' => 'boolean',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];

    /**
     * Get the subscriptions for the plan.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}