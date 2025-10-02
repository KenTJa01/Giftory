<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBooking extends Model
{
    use HasFactory;

    /**
     * Column info:
     *
     * id: integer
     * site_id: integer
     * site_code: integer
     * location_id: integer
     * location_code: string
     * catg_id: integer
     * catg_code: string
     * quantity: numeric
     * unit: string
     * book_type: string (TRF = Transfer, EXP = Expending)
     * reference_no: string
     * created_at: timestamp
     * updated_at: timestamp
     * created_by: integer
     * updated_by: integer
     */

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "stock_bookings";

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_id',
        'site_code',
        'location_id',
        'location_code',
        'catg_id',
        'catg_code',
        'quantity',
        'unit',
        'book_type',
        'reference_no',
        'created_by',
        'updated_by',
    ];
}
