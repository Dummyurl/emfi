<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Custom;
use App\Models\MarketType;
use App\Models\Securities;
use App\Models\Tickers;
use App\Models\HistoricalData;
use App\Models\BondHistoricalData;

class UploadData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     *
     * @return mixed
     */
    public function handle()
    {
        // "C:\xampp\htdocs\Laravel\emfi\public\data_upload";
        $filename = 'data.csv';
        $uploadPath = public_path().DIRECTORY_SEPARATOR.'data_upload' . DIRECTORY_SEPARATOR . 'csv_files'.DIRECTORY_SEPARATOR;
        $path_filename = $uploadPath.$filename;
        
        $this->massinsert($path_filename);

    }

    public function massinsert($path_filename = null)
    {
        if (!empty($path_filename))
        {   
            $markets = MarketType::pluck('id','market_name');
            $size = filesize($path_filename);
            echo "\r\n File Size ==> ". $size;
            $file = fopen($path_filename,"r");
            $i = 0;
            $bytes = ftell($file);
            echo "\r\n File bytes ==> ". $bytes;
            $fields = [];

            while(! feof($file))
            {
                echo "\r\n while ==> ". $i;
                $idata = [];
                $data =  fgetcsv($file);
                if (empty($data)){
                    break;
                }
                if ($i == 0)
                {
                    foreach ($data as $key => $value)
                    {
                        $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
                        $fields[strtolower(trim($value))] = $key;
                    }
                } else
                {
                    $hdata = [];
                    $idata['created'] = ($data[$fields['dates']] == "#N/A N/A" || !isset($data[$fields['dates']])) ? '' : str_replace(',','',$data[$fields['dates']]);
                    if(isset($data[$fields['dates']]) && !empty($data[$fields['dates']])){
                        $arr_date = explode("-", $data[$fields['dates']]);
                        $date = $arr_date[0];
                        $month = $arr_date[1];
                        $full_date = $arr_date[2]."-".$month."-".$date;
                        // echo "\r\n old date ==> ".$data[$fields['dates']];
                        // echo "\r\n new date ==> ". date("Y-m-d", strtotime($full_date));
                        $full_date = date("Y-m-d", strtotime($full_date));
                        $idata['created'] = $full_date;
                    }
                    $idata['bid_price'] = ($data[$fields['px_bid']] == "#N/A N/A" || !isset($data[$fields['px_bid']])) ? '' : str_replace(',','',$data[$fields['px_bid']]);
                    $idata['ask_price'] = ($data[$fields['px_ask']] == "#N/A N/A" || !isset($data[$fields['px_ask']])) ? '' : str_replace(',','',$data[$fields['px_ask']]);
                    $idata['last_price'] = ($data[$fields['px_last']] == "#N/A N/A" || !isset($data[$fields['px_last']])) ? '' : str_replace(',','',$data[$fields['px_last']]);
                    $idata['low_price'] = ($data[$fields['px_low']] == "#N/A N/A" || !isset($data[$fields['px_low']])) ? '': str_replace(',','',$data[$fields['px_low']]);
                    $idata['high_price'] = ($data[$fields['px_high']] == "#N/A N/A" || !isset($data[$fields['px_high']])) ? '' : str_replace(',','',$data[$fields['px_high']]);
                    $idata['net_change'] = ($data[$fields['chg_net_1d']] == "#N/A N/A" || !isset($data[$fields['chg_net_1d']])) ? '' : str_replace(',','',$data[$fields['chg_net_1d']]);
                    $idata['percentage_change'] = ($data[$fields['chg_pct_1d']] == "#N/A N/A" || !isset($data[$fields['chg_pct_1d']])) ? '' : str_replace(',','',$data[$fields['chg_pct_1d']]);
                    // Only historical_data table's colums will be added to this array.
                    $hdata = $idata;
                    $idata['yld_ytm_mid'] = ($data[$fields['yld_ytm_mid']] == "#N/A N/A" || !isset($data[$fields['yld_ytm_mid']])) ? '' : $data[$fields['yld_ytm_mid']];
                    $idata['z_sprd_mid'] = (trim($data[$fields['z_sprd_mid']]) == "#N/A N/A" || !isset($data[$fields['z_sprd_mid']])) ? '' : $data[$fields['z_sprd_mid']];
                    $idata['dur_adj_mid'] = ($data[$fields['dur_adj_mid']] == "#N/A N/A" || !isset($data[$fields['dur_adj_mid']])) ? '' : $data[$fields['dur_adj_mid']];
                    $idata['CUSIP'] = ($data[$fields['cusip']] == "#N/A N/A" || !isset($data[$fields['cusip']])) ? "" : $data[$fields['cusip']] ;
                    
                    // $idata['market_id'] = ($data[$fields['market']] == "#N/A N/A" || !isset($data[$fields['market']])) ? '' : $markets[$data[$fields['market']]];
                    // $idata['country'] = ($data[$fields['country']] == "#N/A N/A" || !isset($data[$fields['country']])) ? '' : $data[$fields['country']];
                    // $idata['ticker'] = ($data[$fields['ticker']] == "#N/A N/A" || !isset($data[$fields['ticker']])) ? '' : $data[$fields['ticker']];
                    // $idata['benchmark'] = ($data[$fields['benchmark']] == "#N/A N/A" || !isset($data[$fields['benchmark']])) ? '' : $data[$fields['benchmark']];
                    // $idata['cpn'] = ($data[$fields['cpn']] == "#N/A N/A" || !isset($data[$fields['cpn']])) ? '' : $data[$fields['cpn']];
                    // $idata['security_name'] = ($data[$fields['security_name']] == "#N/A N/A" || !isset($data[$fields['security_name']])) ? '' : $data[$fields['security_name']];
                    // $idata['maturity_date'] = ($data[$fields['maturity']] == "#N/A N/A" || !isset($data[$fields['maturity']])) ? '' : date('Y-m-d', strtotime($data[$fields['maturity']]));
                    // Update if any record exists Or Create a new Security
                    if (!empty($idata) && is_array($idata))
                    {
                        $security = \App\Models\Securities::where('CUSIP',$idata['CUSIP'])->first();
                        $market_id = 0;
                        if($security){
                            $hdata['security_id'] = $security->id;
                            $market_id = $security->market_id;
                        }
                        if ($security->market_id == 5)
                        {
                            $hdata['DUR_ADJ_MID'] = $idata['dur_adj_mid'];
                            $hdata['YLD_YTM_MID'] = $idata['yld_ytm_mid'];
                            $hdata['Z_SPRD_MID'] = $idata['z_sprd_mid'];

                            \DB::table('bond_historical_data')->insert($hdata);
                        } else
                        {
                            $hdata['YLD_YTM_MID'] = $idata['yld_ytm_mid'];
                            $hdata['Z_SPRD_MID'] = $idata['z_sprd_mid'];
                            \DB::table('historical_data')->insert($hdata);
                        }
                    }
                }
                $i++;
            }
        }
    }

}
