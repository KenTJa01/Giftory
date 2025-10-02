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

class AdjustmentExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
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
           $params .= " AND ah.adj_date >= '".Carbon::parse($this->request->from_date)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
       }
       if (! is_null($this->request->to_date)) {
           $params .= " AND ah.adj_date <= '".Carbon::parse($this->request->to_date)->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
       }
       if (! is_null($this->request->adj_no)) {
           $params .= " AND ah.adj_no ILIKE '%".$this->request->adj_no."%'";
       }
       if (! is_null($this->request->site)) {
           $params .= " AND CAST(st.site_code AS TEXT) ILIKE '%".$this->request->site."%'";
       }

       $sql = ("SELECT ah.adj_no, ah.adj_date,
               st.store_code AS store_code
           FROM adjustment_headers ah, sites st, status s
           WHERE ah.site_id = st.id
               AND ah.flag = s.flag_value
               AND s.module = 'adjustment'
               AND EXISTS (
                   SELECT 1
                   FROM user_sites us
                   WHERE us.user_id = $user->id
                       AND (us.site_id = st.id)
               )$params
           ORDER BY ah.adj_date DESC");
       $data = DB::select($sql);

        return collect($data);
    }

    // Heading
    public function headings(): array
    {
        return [
            'Adj No',
            'Adj Date',
            'Site',
        ];
    }

    public function title(): string
    {
        return "List Adjusment";
    }
}
