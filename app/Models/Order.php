<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'order_type',
        'total_price',
        'points_earned',
        'status'
    ];    

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function items() {
        return $this->hasMany(OrderItem::class);
    }
    
    public function restaurant() {
        return $this->belongsTo(Restaurant::class);
    }
    
}
