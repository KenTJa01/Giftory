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

class ReturnExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
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

        if (! is_null($this->request->dateRet)) {
            $params .= " AND rh.ret_date = '".Carbon::parse($this->request->dateRet)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }

        if (! is_null($this->request->retNumber)) {
            $params .= " AND rh.ret_no ILIKE '%".$this->request->retNumber."%'";
        }

        if (! is_null($this->request->suppSite)) {
            $params .= " AND (CAST(rh.location_code AS TEXT) ILIKE '%".$this->request->suppSite."%'
                OR rh.supp_name ILIKE '%".$this->request->suppSite."%'
            )";
        }

        $sql = ("SELECT rh.ret_no, rh.ret_date, sites.site_code,
                (CASE WHEN rh.location_id IS NOT NULL THEN rh.location_code
                    ELSE rh.supp_name END) AS location
        FROM return_headers rh LEFT JOIN sites ON sites.id = rh.site_id, status s
        WHERE rh.flag = s.flag_value
            AND s.module = 'return'
            AND EXISTS (
                SELECT 1
                FROM user_sites us
                WHERE us.user_id = $user->id
                    AND (us.site_id = sites.id)
            )$params
            ORDER BY rh.ret_date DESC");

		$data = DB::select($sql);

        return collect($data);
    }

    // Heading
    public function headings(): array
    {
        return [
            'Ret No',
            'Ret Date',
            'To Site',
            'To Location/Supplier',
        ];
    }

    public function title(): string
    {
        return "List Return";
    }
}
