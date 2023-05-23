<?php

namespace App\Http\Controllers\charts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class piechartcontroller extends Controller
{
	
    public function __construct()
    {
	
       
	}

	public function index(Request $request)
    { 

        
  $data1  = DB::select('select appltypedesc, sum(applicationcnt) as applicationcnt, appltypecode from applicationcount
group by appltypedesc,appltypecode order by appltypecode ');

             $i=0;
foreach ($data1 as $data2)
          {     
        $data['rows'][$i][0] = $data2->appltypedesc;
        $data['rows'][$i][1] = (int) $data2->applicationcnt;
$i++;
      }
            $data['charttype'] = 'piechart-chart';

            $data['options'] = [
                   
                    'title' => 'Total registered Applications in KSAT',
                    'is3D' => true,
                    'sliceVisibilityThreshold' => 0,
                    'legend' =>
                      [ 'position' => 'right', 
                      'labeledValueText' => 'both' ]
                       ,
                      'pieSliceBorderColor' => 'white',

                ];
                    

               $data['cols']   =  ['Type of Application', 'No. of Applications'];
                    
                              
                

// chart2

 $data1  = DB::select('select appltypedesc, sum(applicationcnt) as applicationcnt, appltypecode from applicationcount where statuscode = 1 or statuscode is null 
group by appltypedesc,appltypecode order by appltypecode ');

             $i=0;
foreach ($data1 as $data2)
          {     
        $data['rows1'][$i][0] = $data2->appltypedesc;
        $data['rows1'][$i][1] = (int) $data2->applicationcnt;
$i++;
      }
            $data['charttype'] = 'piechart-chart';

            $data['options1'] = [
                   
                    'title' => 'Total Pending Applications in KSAT',
                    'is3D' => false,
                    'sliceVisibilityThreshold' => 0,
                    'legend' =>
                      [ 'position' => 'right' ] ,
                      'pieSliceBorderColor' => 'white',

                ];
                    

               $data['cols1']   =  ['Type of Application', 'No. of Applications'];

                  $data['samplerows1']   =   [
                      ['Business', 256070], ['Education', 108034],
                        
                                 
                ];
               
            $data['chartcount'] = 2;

               return view('dashboard.chart',$data);
		
	}
	
	
}