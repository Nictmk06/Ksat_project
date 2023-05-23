<?php

namespace App\Http\Controllers\charts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class donutchartcontroller extends Controller
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
   $year = DB::select('select extract (year from current_date) as currentyear' );

  $data1  = DB::select('select cast(regmonth as integer) as regmonth, cast(sum(applicationcnt) as integer)  as applicationcount from application_instituted_count
where regyear = '. $year[0]->currentyear .'   group by regmonth order by regmonth ');

             $i=0;
foreach ($data1 as $data2)
          {     
        $data['rows'][$i][0] = $standard_months[ $data2->regmonth ];
        $data['rows'][$i][1] = $data2->applicationcount;
$i++;
      }
          
    
            $data['charttype'] = 'piechart-chart';

            $data['options'] = [
                   
                    'title' => 'Month wise Applications registered for the year ' . $year[0]->currentyear,
                    'is3D' => true,
                    'sliceVisibilityThreshold' => 0,
                    'legend' =>
                      [ 'position' => 'right', 
                      'labeledValueText' => 'both' ]
                       ,
                      'pieSliceBorderColor' => 'white',

                ];
              
               
               $data['cols']   =  ['Month','Applications' ];
                    
                      $data['options1'] = [
                   
                    'title' => ' Applications disposed for the year '. $year[0]->currentyear,
                    'is3D' => true,
                    'sliceVisibilityThreshold' => 0,
                    'legend' =>
                      [ 'position' => 'right', 
                      'labeledValueText' => 'both' ]
                       ,
                      'pieSliceBorderColor' => 'white',

                ];

               $data['cols1']   =  ['Status','Applications' ];

 $data1  = DB::select('select case when statusname IS NULL THEN \'Pending\' ELSE statusname end as statusname, cast(sum(applicationcnt) as integer)  as applicationcount from application_instituted_count
where regyear = '. $year[0]->currentyear .'   group by statusname order by statusname ');

             $i=0;
foreach ($data1 as $data2)
          {     
        $data['rows1'][$i][0] =  $data2->statusname ;
        $data['rows1'][$i][1] = $data2->applicationcount;
$i++;
      }
                                              
           
                        
            $data['chartcount'] = 2;
 
               return view('dashboard.chart',$data);
		
	}
	
	
}