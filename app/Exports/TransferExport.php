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

class TransferExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
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
        if (! is_null($this->request->dateFromData)) {
            $params .= " AND th.trf_date >= '".Carbon::parse($this->request->dateFromData)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($this->request->dateToData)) {
            $params .= " AND th.trf_date <= '".Carbon::parse($this->request->dateToData)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($this->request->trfNumber)) {
            $params .= " AND th.trf_no ILIKE '%".$this->request->trfNumber."%'";
        }
        if (! is_null($this->request->siteFrom)) {
            // $params .= " AND CAST(orig.site_code AS TEXT) ILIKE '%".$this->request->siteFrom."%'";
            $params .= " AND th.origin_site_id = ".$this->request->siteFrom;
        }
        if (! is_null($this->request->siteTo)) {
            // $params .= " AND CAST(dest.site_code AS TEXT) ILIKE '%".$this->request->siteTo."%'";
            $params .= " AND th.destination_site_id = ".$this->request->siteTo;
        }
        if (! is_null($this->request->status)) {
            $params .= " AND s.flag_value = ".$this->request->status;
        }

        $sql = ("SELECT th.trf_no, th.trf_date,
                orig.store_code AS store_code_orig,
                dest.store_code AS store_code_dest,
                s.flag_desc AS status
            FROM transfer_headers th, sites orig, sites dest, status s
            WHERE th.origin_site_id = orig.id
                AND th.destination_site_id = dest.id
                AND th.flag = s.flag_value
                AND s.module = 'transfer'
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.user_id = $user->id
                        AND (us.site_id = orig.id OR us.site_id = dest.id)
                )$params
            ORDER BY th.trf_date DESC");
		$data = DB::select($sql);

        return collect($data);
    }

    // Heading
    public function headings(): array
    {
        return [
            'Trf No',
            'Trf Date',
            'From Site',
            'To Site',
            'Status',
        ];
    }

    public function title(): string
    {
        return "List Transfer";
    }
}
