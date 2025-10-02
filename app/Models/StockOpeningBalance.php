<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpeningBalance extends Model
{
    /**
     * Column info:
     *
     * id: integer
     * filename: string
     * site_code: integer
     * catg_code: string
     * catg_desc: string
     * location_code: string
     * qty: float
     * flag: integer
     * created_at: timestamp
     * updated_at: timestamp
     */

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "stock_opening_balances";

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
        'filename',
        'site_code',
        'catg_code',
        'catg_desc',
        'location_code',
        'qty',
        'flag',
    ];
}
