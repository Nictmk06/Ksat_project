<?php

namespace App\Http\Controllers\charts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class  barchartcontroller extends Controller 
{
	
    public function __construct()
    {
	
       
	}

	public function index(Request $request)
    { 

 $noofyear = 3;
 $noofrecord = 5;

              $data['charttype'] = 'bar-chart';
       		$data['options'] = [
                     'title' => 'Top '. $noofrecord .' Number of Registered Applications for the last ' . $noofyear . ' years',
                    'chartArea' => ['width' => '50%']
                             ,
                    'width' => 900,
                    'height' => 700,
                    'hAxis' => [
                        'title' => 'No. of Applications',
                        'minValue' => 0
                    ],
                    'vAxis' => [
                        'title' => 'Department'
                    ],
                    'bars' => 'horizontal', //required if using material chart

                    'axes' => [
                        'y' => [0 => ['side' => 'right']]
                    ]
                ];

  // $data['cols']   = 
  //                 [' Department', '2020', '2019','2018'] ;

  $data1  = DB::select('select regyear,row_number() over 
 (order by regyear desc ) as rno from  
 (select distinct regyear from appldepartcount order by regyear desc) 
 as yearvalue ');

 $i=1;
 $data['cols'][0] = 'Departments';
foreach ($data1 as $data2)
          {     
        $data['cols'][$i] =  $data2-> regyear;
       
$i++;
if ($i==$noofyear+1) break;
      }
$noofcolumn =  $i-1;

                
// Top ranks of department
$query = 'select regyear,departcode, departmentname, applicationcnt,rank1 
from f_appldepartcount1(' . $noofrecord . ',' . $noofyear . ') as fa(regyear integer, departcode integer,
   departmentname character varying, applicationcnt integer,rank1 integer) 
   order by  regyear desc,rank1 asc ';


$query1 = 'select distinct departmentname from (' . $query . ') as departmentname ';

$data1  = DB::select($query1);

 $i=0;
 foreach ($data1 as $data2)
          {     
        $data['rows'][$i][0] =  $data2-> departmentname;
     // initialize zero for data values   
        for($j=1;$j<=$noofcolumn;$j++)
            $data['rows'][$i][$j] =  0;
        $i++; }

$noofrow = $i--;

  
//take data

        $data1  = DB::select($query); 

 foreach ($data1 as $data2)
          {  
            $i=0;  $j = 1;
            for($i=0;$i<$noofrow;$i++)
              { for ($j=1; $j<=$noofcolumn;$j++)
                 {
                   if (($data['rows'][$i][0] ==  $data2-> departmentname) && ($data['cols'][$j] ==  $data2-> regyear))
        $data['rows'][$i][$j] =  $data2-> applicationcnt;
         
                 }
              }
               }

    $data['chartcount'] = 1;

               return view('dashboard.chart',$data);
}

	
	
	
	
}