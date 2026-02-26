<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmartRentOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'smartrent_orders';

    protected $fillable = [
        'order_number','invoice_number','user_id','vehicle_id','vehicle_name','vehicle_type','vehicle_price','duration','vehicle_total',
        'service_type','driver_price_per_day','driver_total','total_price','customer_name','customer_email','customer_phone','customer_address',
        'start_date','end_date','start_time','end_time','pickup_location','ktp_path','sim_path','other_document_path','status'
    ];

    protected $dates = ['start_date','end_date','created_at','updated_at','deleted_at'];
}
