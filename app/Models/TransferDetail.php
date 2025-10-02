<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferDetail extends Model
{
    /**
     * Column info:
     *
     * id: integer
     * trf_id: integer
     * catg_id: integer
     * catg_code: string
     * catg_desc: string
     * unit_price: number
     * quantity: number
     * unit: string
     * from_location_id: integer
     * from_location_code: string
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
    protected $table = "transfer_details";

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
        'trf_id',
        'catg_id',
        'catg_code',
        'catg_desc',
        'unit_price',
        'quantity',
        'unit',
        'from_location_id',
        'from_location_code',
        'created_by',
        'updated_by',
    ];
}
