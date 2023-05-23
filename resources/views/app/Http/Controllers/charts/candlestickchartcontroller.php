<?php

namespace App\Http\Controllers\charts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class candlestickchartcontroller extends Controller
{
	
    public function __construct()
    {
	
       
	}

	public function index(Request $request)
    { 
$standard_months[1] = "Jan";
$standard_months[2] = "Feb";
$standard_months[3] =  "Mar";
$standard_months[4] = "Apr";
$standard_months[5] = "May";
$standard_months[6] =  "Jun";
$standard_months[7] = "Jul";
$standard_months[8] = "Aug";
$standard_months[9] =  "Sep";
$standard_months[10] =  "Oct";
$standard_months[11] = "Nov";
$standard_months[12] = "Dec";

   // print_r( $standard_months );
        
  $data1  = DB::select('select cast(regmonth as integer) as regmonth, cast(sum(applicationcnt) as integer)  as applicationcount from application_instituted_count
where regyear =  2019  group by regmonth order by regmonth ');

             $i=0;
foreach ($data1 as $data2)
          {     
        $data['rows1'][$i][0] = $standard_months[ $data2->regmonth ];
        $data['rows1'][$i][1] = $data2->applicationcount;
$i++;
      }
          
  $data['rows'] = [ 
                 
                    [ ['Thu', 77, 77, 66, 50],
                    ['Fri', 68, 66, 22, 15], true],                     
                ];


                $data['options'] = [
                    'legend' => 'none',

                               ];          
           
 $data['cols'] = [  ];

            $data['charttype'] = 'candlestick-chart';

       
                    

                              
             print_r($data);                 
                
  


            $data['chartcount'] = 1;


                
               return view('dashboard.chart',$data);
		
	}
	
	
}