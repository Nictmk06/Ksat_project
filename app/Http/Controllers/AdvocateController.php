<?php

namespace App\Http\Controllers;

use App\Advocate as AdvocateModel;
use Illuminate\Http\Request;
use Session;
class AdvocateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([

        'modal_adv_bar_reg_no' => 'required|regex:/([a-zA-Z]{3})\/([0-9]{1,6})\/([0-9]{4})/',
        'modal_adv_name' => 'required'

        ]);
       $Advocate = new AdvocateModel([
           'advocatename' =>$request->input('modal_adv_name'),
           'advocatetypecode'=>2,
           'advocateregno' =>$request->input('modal_adv_bar_reg_no'),
           'advestablishment' => Session::get('EstablishCode'),
           'advocatemobile' =>$request->input('modal_mobile_no'),
           'advocateemail' =>$request->input('modal_email_no')

            ]);

       
       // $data['advocate'] = AdvocateModel::getAdvocates();
         if($Advocate->save())
         {
           
            return response()->json([
                        'status' => "sucess",
                      

                        ]);
         }
         else
         {
            return response()->json([
                        'status' => "fail"
                       

                        ]);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Advocate  $advocate
     * @return \Illuminate\Http\Response
     */
    public function show(Advocate $advocate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Advocate  $advocate
     * @return \Illuminate\Http\Response
     */
    public function edit(Advocate $advocate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Advocate  $advocate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advocate $advocate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Advocate  $advocate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advocate $advocate)
    {
        //
    }
}
