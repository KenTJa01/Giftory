<?php

namespace App\Console\Commands;

use App\Interfaces\InterfaceClass;
use App\Models\MovementType;
use App\Models\Location;
use App\Models\ProductCategory;
use App\Models\Site;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\StockOpeningBalance;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OpeningBalanceStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:opening-balance-upload {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock opening balance upload';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** Get params */
        $filename = $this->argument('filename');

        $executionStartTime = microtime(true);
        $this->generateLog($filename, 'Start '.$this->description);

        /** Check file exists or not */
        // $checkFile = file_exists(storage_path('app/'.$filename)); /** LOCAL */
        $checkFile = file_exists('/home/deployer/stock_opbal/process/'.$filename); /** SERVER */
        if (! $checkFile) {
            Log::warning('File not exists', ['filename' => $filename]);
            return;
        }

        /** ----------------------------------------------------------------------------------------------- */
        /** -- Import data from csv to database -- */
        $executionSubStartTime = microtime(true);
        $subProcessDesc = 'import csv';
        $this->generateLog($filename, '1-Start '.$subProcessDesc);

        DB::beginTransaction();
        try {
            // $data = file(storage_path('app/'.$filename)); /** LOCAL */
            $data = file('/home/deployer/stock_opbal/process/'.$filename); /** SERVER */
            foreach ($data as $d) {
                $row = explode('|', $d);
                if ($row[0] != 'site_code') {
                    if ($row[0] != '' && $row[1] != '' && $row[2] != '' && $row[3] != '' && $row[4] != '') {
                        StockOpeningBalance::create([
                            'filename' => $filename,
                            'site_code' => $row[0],
                            'catg_code' => $row[1],
                            'catg_desc' => $row[2],
                            'location_code' => $row[3],
                            'qty' => $row[4],
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Failed '.$subProcessDesc, ['filename' => $filename]);
            return;
        }
        $executionSubEndTime = microtime(true);
        $seconds = $executionSubEndTime - $executionSubStartTime;
        $this->generateLog($filename, '1-Finish '.$subProcessDesc);

        $this->info('[KERNEL] 1-Execution time '.$subProcessDesc.': '.$seconds);
        Log::info('[KERNEL] 1-Execution time '.$subProcessDesc.': '.$seconds);

        /** ----------------------------------------------------------------------------------------------- */
        /** -- Translate data to price tables -- */
        $stockOpbal = StockOpeningBalance::where('filename', $filename)->where('flag', 0)->count();
        if ($stockOpbal == 0) {
            Log::warning('File not processed', ['filename' => $filename]);
            return;
        }
        $executionSubStartTime = microtime(true);
        $subProcessDesc = 'upload stock';
        $this->generateLog($filename, '2-Start '.$subProcessDesc);

        DB::beginTransaction();
        try {
            $movCode = MovementType::where('mov_code', InterfaceClass::OPENINGBALANCE_MOVEMENT)->first()?->mov_code;

            StockOpeningBalance::where('filename', $filename)->where('flag', 0)
                ->chunkById(1000, function ($uploads) use ($filename, $movCode) {
                    foreach ($uploads as $upload) {
                        $canProcess = true;
                        $site = Site::where('site_code', $upload->site_code)->first();
                        $productCategory = ProductCategory::where('catg_code', $upload->catg_code)->first();
                        $location = Location::where('location_code', $upload->location_code)->first();

                        if (is_null($site)) {
                            Log::warning('Site code not found', ['filename' => $filename, 'site_code' => $upload->site_code]);
                            $canProcess = false;
                        }
                        if (is_null($productCategory)) {
                            Log::warning('Product category code not found', ['filename' => $filename, 'catg_code' => $upload->catg_code]);
                            $canProcess = false;
                        }
                        if (is_null($location)) {
                            Log::warning('Location code not found', ['filename' => $filename, 'location_code' => $upload->location_code]);
                            $canProcess = false;
                        }

                        if ($canProcess) {
                            /** Check stock already exists or not */
                            $stock = Stock::where('site_id', $site->id)->where('location_id', $location->id)->where('catg_id', $productCategory->id)->first();

                            if (!is_null($stock)) {
                                Log::warning('Stock already exists', ['filename' => $filename, 'site_code' => $upload->site_code, 'catg_code' => $upload->catg_code, 'location_code' => $upload->location_code]);

                                $upload->flag = 2;
                                $upload->save();
                            } else {
                                /** Generate stock */
                                Stock::create([
                                    'site_id' => $site->id,
                                    'site_code' => $site->site_code,
                                    'location_id' => $location->id,
                                    'location_code' => $location->location_code,
                                    'catg_id' => $productCategory->id,
                                    'catg_code' => $productCategory->catg_code,
                                    'quantity' => $upload->qty,
                                    'unit' => $productCategory->unit,
                                    'avg_cost' => 0,
                                    'so_flag' => 0,
                                    'created_by' => 1,
                                    'updated_by' => 1,
                                ]);

                                /** Generate movement */
                                StockMovement::create([
                                    'mov_date' => date('Y-m-d'),
                                    'site_id' => $site->id,
                                    'site_code' => $site->site_code,
                                    'location_id' => $location->id,
                                    'location_code' => $location->location_code,
                                    'catg_id' => $productCategory->id,
                                    'catg_code' => $productCategory->catg_code,
                                    'quantity' => $upload->qty,
                                    'unit' => $productCategory->unit,
                                    'mov_code' => $movCode,
                                    'purch_price' => 0,
                                    'sales_price' => 0,
                                    'ref_no' => '-',
                                    'created_by' => 1,
                                    'updated_by' => 1,
                                ]);

                                $upload->flag = 1;
                                $upload->save();
                            }
                        } else {
                            $upload->flag = 2;
                            $upload->save();
                        }
                    }
                }, $column = 'id');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Failed '.$subProcessDesc, ['filename' => $filename]);
            return;
        }
        $executionSubEndTime = microtime(true);
        $seconds = $executionSubEndTime - $executionSubStartTime;
        $this->generateLog($filename, '2-Finish '.$subProcessDesc);

        $this->info('[KERNEL] 2-Execution time '.$subProcessDesc.': '.$seconds);
        Log::info('[KERNEL] 2-Execution time '.$subProcessDesc.': '.$seconds);

        /** ----------------------------------------------------------------------------------------------- */
        rename('/home/deployer/stock_opbal/process/'.$filename, '/home/deployer/stock_opbal/finish/'.$filename); /** SERVER */

        $executionEndTime = microtime(true);
        $seconds = $executionEndTime - $executionStartTime;
        $this->generateLog($filename, 'Finish '.$this->description);

        $this->info('[KERNEL] Execution time '.$this->description.': '.$seconds);
        Log::info('[KERNEL] Execution time '.$this->description.': '.$seconds);

        $this->info(PHP_EOL);
        Log::info(PHP_EOL);
    }

    private function generateLog(string $filename, string $message)
    {
        $dateNow = Carbon::now()->setTimezone('Asia/Jakarta');
        $todayWithTime = $dateNow->format('Y-m-d H:i:s');
        $infoProcess = '[KERNEL] '.$message.' (File '.$filename.' at '.$todayWithTime.')';

        $this->info($infoProcess);
        Log::info($infoProcess);
    }
}
