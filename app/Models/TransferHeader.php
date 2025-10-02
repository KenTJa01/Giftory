<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferHeader extends Model
{
    /**
     * Column info:
     *
     * id: integer
     * trf_no: string
     * trf_date: date
     * origin_site_id: integer
     * origin_site_code: integer
     * destination_site_id: integer 
     * destination_site_code: integer
     * approved_by: integer
     * approved_date: timestamp
     * flag: integer
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
    protected $table = "transfer_headers";

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
        'trf_no',
        'trf_date',
        'origin_site_id',
        'origin_site_code',
        'destination_site_id',
        'destination_site_code',
        'approved_by',
        'approved_date',
        'flag',
        'created_by',
        'updated_by',
    ];
}
