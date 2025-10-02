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

class StockExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
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
        if (! is_null($this->request->product)) {
            $params .= " AND pc.id = ".$this->request->product;
        }
        if (! is_null($this->request->site)) {
            $params .= " AND s.site_id = ".$this->request->site;
        }
        if (! is_null($this->request->location)) {
            $params .= " AND s.location_id = ".$this->request->location;
        }
        if ($profileLocation > 0) {
            $params .= " AND EXISTS (SELECT 1 FROM profile_locations pl WHERE s.location_id = pl.location_id AND pl.profile_id = $user->profile_id)";
        }

        $sql = ("SELECT pc.catg_code, pc.catg_name, sites.store_code, l.location_code, s.quantity,
                COALESCE((SELECT SUM(sb.quantity) FROM stock_bookings sb
                    WHERE sb.site_id = s.site_id AND sb.catg_id = s.catg_id AND sb.location_id = s.location_id),0) AS book_qty,
                s.quantity - COALESCE((SELECT SUM(sb.quantity)
                    FROM stock_bookings sb
                    WHERE sb.site_id = s.site_id
                        AND sb.catg_id = s.catg_id
                        AND sb.location_id = s.location_id), 0) AS available,
                s.unit
            FROM stocks s, sites, product_categories pc, locations l
            WHERE s.site_id = sites.id
                AND s.catg_id = pc.id
                AND s.location_id = l.id
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.site_id = sites.id
                        AND us.user_id = $user->id
                )$params
            ORDER BY s.id DESC");
        $data = DB::select($sql);

        return collect($data);
    }

    // Heading
    public function headings(): array
    {
        return [
            'Product Code',
            'Product Name',
            'Site',
            'Location',
            'Stock',
            'Booked',
            'Available',
            'Unit',
        ];
    }

    public function title(): string
    {
        return "List Stock";
    }
}
