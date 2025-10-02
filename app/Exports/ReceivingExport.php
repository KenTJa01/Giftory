<?php

namespace App\Exports;

use App\Models\ReceivingHeader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReceivingExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $request;

    public function __construct($validated)
    {
        $this->request = $validated;
    }

    public function collection()
    {
        $user = Auth::user();

        /** Prepare for parameters */
        $params = '';

        if (! is_null($this->request->dateRec)) {
            $params .= " AND rh.rec_date = '".Carbon::parse($this->request->dateRec)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }

        if (! is_null($this->request->recNumber)) {
            $params .= " AND rh.rec_no ILIKE '%".$this->request->recNumber."%'";
        }

        if (! is_null($this->request->suppSite)) {
            $params .= " AND (CAST(orig.store_code AS TEXT) ILIKE '%".$this->request->suppSite."%'
                OR rh.supp_name ILIKE '%".$this->request->suppSite."%'
            )";
        }

        $sql = ("SELECT rh.rec_no, rh.rec_date,
            (CASE WHEN rh.origin_site_id IS NOT NULL THEN orig.store_code
                ELSE rh.supp_name END) AS origin,
            dest.store_code AS to_store_code
        FROM receiving_headers rh LEFT JOIN sites orig ON orig.id = rh.origin_site_id, sites dest, status s
        WHERE rh.destination_site_id = dest.id
            AND rh.flag = s.flag_value
            AND s.module = 'receiving'
            AND EXISTS (
                SELECT 1
                FROM user_sites us
                WHERE us.user_id = $user->id
                    AND (us.site_id = orig.id OR us.site_id = dest.id)
            )$params
            ORDER BY rh.rec_date DESC");

		$data = DB::select($sql);

        return collect($data);
    }

    // Heading
    public function headings(): array
    {
        return [
            'Rec No',
            'Rec Date',
            'From Site/Supplier',
            'To Site',
        ];
    }

    public function title(): string
    {
        return "List Receiving";
    }

}
