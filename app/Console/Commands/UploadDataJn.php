<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Custom;
use App\Models\MarketType;
use App\Models\Securities;
use App\Models\Tickers;
use App\Models\HistoricalData;
use App\Models\BondHistoricalData;

class UploadDataJn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:datajn';

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
        $filename = 'Data-Jn.csv';
        $filename = 'Data - 20180529.csv';
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
                    // if($i < 23793){
                    //     $i++;
                    //     continue;
                    // }
                    $hdata = [];
                    $idata['created'] = ($data[$fields['dates']] == "#N/A N/A" || !isset($data[$fields['dates']])) ? '' : str_replace(',','',$data[$fields['dates']]);
                    if(isset($data[$fields['dates']]) && !empty($data[$fields['dates']])){
                        $arr_date = explode("-", $data[$fields['dates']]);
                        $date = $arr_date[1];
                        $month = $arr_date[0];
                        $full_date = $arr_date[2]."-".$month."-".$date;
                        echo "\r\n old date ==> ".$data[$fields['dates']];
                        echo "\r\n new date ==> ". date("Y-m-d", strtotime($full_date));
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
                    $idata['yld_ytm_mid'] = ($data[$fields['yld_ytm_mid']] == "#N/A N/A" || !isset($data[$fields['yld_ytm_mid']])) ? '' : str_replace(',','',$data[$fields['yld_ytm_mid']]);

                    $idata['z_sprd_mid'] = (trim($data[$fields['z_sprd_mid']]) == "#N/A N/A" || !isset($data[$fields['z_sprd_mid']])) ? null : str_replace(',','',$data[$fields['z_sprd_mid']]);

                    $idata['dur_adj_mid'] = ($data[$fields['dur_adj_mid']] == "#N/A N/A" || !isset($data[$fields['dur_adj_mid']])) ? '' : str_replace(',','',$data[$fields['dur_adj_mid']]);

                    $idata['CUSIP'] = ($data[$fields['cusip']] == "#N/A N/A" || !isset($data[$fields['cusip']])) ? "" : $data[$fields['cusip']] ;
                    // Update if any record exists Or Create a new Security
                    // print_r($idata);
                    // exit();

                    if (!empty($idata) && is_array($idata))
                    {
                        $security = \App\Models\Securities::where('CUSIP',$idata['CUSIP'])->first();
                        $market_id = 0;
                        if($security){
                            $hdata['security_id'] = $security->id;
                            $hdata['sp_rating_id'] = $security->sp_rating_id;
                            $hdata['current_oecd_member_cor_class'] = $security->current_oecd_member_cor_class;
                            $market_id = $security->market_id;
                        }


                        if ($security->market_id == 5)
                        {
                            $hdata['DUR_ADJ_MID'] = $idata['dur_adj_mid'];
                            $hdata['YLD_YTM_MID'] = $idata['yld_ytm_mid'];
                            if($idata['z_sprd_mid'] > 0){
                                $hdata['Z_SPRD_MID'] = $idata['z_sprd_mid'];
                            }
                            \DB::table('bond_historical_data')->insert($hdata);
                        } else
                        {
                            $hdata['YLD_YTM_MID'] = $idata['yld_ytm_mid'];
                            if($idata['z_sprd_mid'] > 0){
                                $hdata['Z_SPRD_MID'] = $idata['z_sprd_mid'];
                            }
                            \DB::table('historical_data')->insert($hdata);
                        }
                    }
                }
                $i++;
            }
        }
    }

}
