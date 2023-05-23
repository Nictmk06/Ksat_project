<?php

namespace App\Http\Controllers\charts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class  stackedbarchartcontroller extends Controller 
{
	
    public function __construct()
    {
	
       
	}

	public function index(Request $request)
    { 

 $noofyear = 5;
 $noofrecord = 5;

              $data['charttype'] = 'bar-chart';
       		$data['options'] = [
                     'title' => 'Top '. $noofrecord .'  Stages of Pending Applications  for the last ' . $noofyear . ' years',
                    'chartArea' => ['width' => '50%']
                             ,
                    'width' => 900,
                    'height' => 900,
                     'legend' => ['position' => 'top', 'maxLines' => 20],
                    'bar' => ['groupWidth' => '75%'],
                     'vAxis' => ['minValue' => 0],
                    'isStacked' => TRUE
                ];

  // $data['cols']   = 
  //                 [' Department', '2020', '2019','2018'] ;

  $data1  = DB::select('select distinct listpurpose from (select * from appl_listpurposecount 
 where purposerank <= ' . $noofrecord . ' and purposecount > 10
 order by regyear desc, purposecount desc) as t ');

 $i=1;
 
 $data['cols'][0] =  'Year' ;

foreach ($data1 as $data2)
          {     
        $data['cols'][$i] =  $data2-> listpurpose;
 //    if ($i == $noofrecord )  break;
$i++;
      }
$noofcolumn =  $i-1;


$query = 'select  regyear, listpurpose,purposecount,purposerank from appl_listpurposecount 
where purposerank <= ' . $noofrecord .' and purposecount > 10 order by regyear desc, purposerank ';


$query1 = 'select distinct  regyear, rank() over(partition by regyear) from ('. $query .') as t  order by regyear desc ';

$data1  = DB::select($query1);

 $i=0;
 foreach ($data1 as $data2)
          {     
        $data['rows'][$i][0] =   (string) $data2-> regyear   ;
     // initialize zero for data values   
        for($j=1;$j<=$noofcolumn;$j++)
            $data['rows'][$i][$j] =  0;
          if ($i == $noofyear+1) break;
           $i++; }

$noofrow = $i;

  //take data

        $data1  = DB::select($query); 

 foreach ($data1 as $data2)
          {  
            $i=0;  $j = 1;
            for($i=0;$i<$noofrow;$i++)
              { for ($j=1; $j<=$noofcolumn;$j++)
                 { $temp =  (string) $data2-> regyear  ;
                   if (($data['rows'][$i][0] == $temp) && ($data['cols'][$j] ==  $data2-> listpurpose)) 
        $data['rows'][$i][$j] = (int) $data2-> purposecount;
         
                 }
              }
               }

    $data['chartcount'] = 1;

            $data['cols1'] = ['Date', 'Fantasy & Sci Fi', 'Romance', 'Mystery/Crime', 'General',
                    'Western', 'Literature'];

                 $data['rows1'] = [
                    [2010, 12, 22, 23, 30, 16],
                    [2020, 16, 22, 23, 30, 16],
                    [2030, 28, 19, 29, 30, 12]
                ];


//print_r($data);
               return view('dashboard.chart',$data);
}

	
	
	
	
}