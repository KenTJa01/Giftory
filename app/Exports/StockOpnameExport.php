<?php

namespace App\Exports;

use App\Models\ProfileLocation;
use App\Models\ReceivingHeader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StockOpnameExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
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
        if (! is_null($this->request->from_date)) {
            $params .= " AND sh.so_date >= '".Carbon::parse($this->request->from_date)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($this->request->to_date)) {
            $params .= " AND sh.so_date <= '".Carbon::parse($this->request->to_date)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($this->request->so_no)) {
            $params .= " AND sh.so_no ILIKE '%".$this->request->so_no."%'";
        }
        if (! is_null($this->request->site)) {
            $params .= " AND sh.site_id = ".$this->request->site;
        }
        if (! is_null($this->request->location)) {
            $params .= " AND sh.location_code ILIKE '%".$this->request->location."%'";
        }
        if (! is_null($this->request->status)) {
            $params .= " AND sh.flag = ".$this->request->status;
        }

        $sql = ("SELECT sh.so_no, sh.so_date, s.store_code,
                l.location_code,
                sts.flag_desc AS status
            FROM stock_opname_headers sh, sites s, locations l, status sts
            WHERE sh.site_id = s.id
                AND sh.location_id = l.id
                AND sh.flag = sts.flag_value
                AND sts.module = 'stock_opname'
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.user_id = $user?->id
                        AND us.site_id = sh.site_id
                )$params
            ORDER BY sh.so_date DESC");
		$data = DB::select($sql);

        return collect($data);
    }

    // Heading
    public function headings(): array
    {
        return [
            'So No',
            'So Date',
            'Site',
            'Location',
            'Status',
        ];
    }

    public function title(): string
    {
        return "List Stock Opname";
    }
}
