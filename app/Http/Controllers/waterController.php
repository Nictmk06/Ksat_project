<?php
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Http\Requests;
use PDF;
use FPDF;
use FPDI;
use setasign\Fpdi\Tfpdf;
//use setasign\fpdf;
use setasign\fpdi\src;
 
class waterController extends Controller
{
	public function index(){
    	return view('PdfDemo');
    }
	
	public function watermark(){
		return view('watermark');
	}
	
	
	public function PlaceWatermark( $file,$filename,$judgementpath,$v) {
		
	/*	$file= $request->filename;
		$fname=$file->getClientOriginalName();
//dd($fname);
		$storing = $file->storeAs('pdf', $fname);
	//	dd($storing);
		$saving_name = 'w_'.$fname;*/
		
		//This page contains edit the existing file by using fpdi.
		require('WatermarkPDF.php');
		$pdfFile=null;
		$storing=null;
if($v==1){
		$pdfFile =$judgementpath.$filename;
	}else{
		$pdfFile ='/var/www/data/ksat/pdf/'.$filename;
		$storing = $file->move('/var/www/data/ksat/pdf/', $filename);
	}	
		//dd($pdfFile);
      // $saving_name = $filename;
		//dd($pdfFile);
		$watermarkText = " ";
		$pdf = new WatermarkPDF($pdfFile, $watermarkText);
		//$pdf = new FPDI();
		$pdf->AddPage();
		//$pdf->SetFont('Arial', '', 40);

		if($pdf->numPages>1) {
			for($i=2;$i<=$pdf->numPages;$i++) {
				$pdf->_tplIdx = $pdf->importPage($i);
				$pdf->AddPage();
			}
		}
       
         $saving_name = $filename;
if($v==1){
		$pdfFilestore = $judgementpath.$saving_name;
	}else{
		$pdfFilestore = 'pdf/'.$saving_name;
	}	
		//return $pdfFilestore;
		//$b64= chunk_split(base64_encode($pdf->Output()));
		//dd($b64);
		$pdf->Output('F',$pdfFilestore); //storing in public folder
		//$pdf->Output(); //If you Leave blank then it should take default "I" i.e. Browser
		//$pdf->Output("sampleUpdated.pdf", 'D'); //Download the file. open dialogue window in browser to save, not open with PDF browser viewer
		//$pdf->Output("save_to_directory_path.pdf", 'F'); //save to a local file with the name given by filename (may include a path)
		//$pdf->Output("sampleUpdated.pdf", 'I'); //I for "inline" to send the PDF to the browser
		//$pdf->Output("", 'S'); //return the document as a string. filename is ignored.

		
		return view('watermark');
		////return "uploaded";
	}
	
}
