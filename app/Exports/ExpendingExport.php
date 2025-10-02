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

class ExpendingExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
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

        $profileLocation = ProfileLocation::where('profile_id', $user?->profile_id)->count();

        /** Prepare for parameters */
        $params = '';
        if (! is_null($this->request->expNumber)) {
            $params .= " AND eh.req_no ILIKE '%".$this->request->expNumber."%'";
        }
        if (! is_null($this->request->site)) {
            $params .= " AND sites.site_code = ".$this->request->site;
        }
        if (! is_null($this->request->dateFromData)) {
            $params .= " AND eh.req_date >= '".Carbon::parse($this->request->dateFromData)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($this->request->dateToData)) {
            $params .= " AND eh.req_date <= '".Carbon::parse($this->request->dateToData)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if ($profileLocation > 0) {
            $params .= " AND EXISTS (SELECT 1 FROM profile_locations pl WHERE eh.location_id = pl.location_id AND pl.profile_id = $user->profile_id)";
        }

        $sql = ("SELECT eh.req_no, eh.req_date, sites.store_code, s.flag_desc AS status
            FROM expending_headers eh, sites, status s
            WHERE eh.origin_site_id = sites.id
                AND eh.flag = s.flag_value
                AND s.module = 'expending'
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.site_id = sites.id
                        AND us.user_id = $user->id
                )$params
            ORDER BY eh.req_date DESC");
            $data = DB::select($sql);

        return collect($data);
    }

    // Heading
    public function headings(): array
    {
        return [
            'Exp No',
            'Exp Date',
            'Site',
            'Status',
        ];
    }

    public function title(): string
    {
        return "List Expending";
    }
}
