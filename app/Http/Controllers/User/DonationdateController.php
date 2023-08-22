<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Donationdate;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::guard('user')->user()->id;
        try{
        $dates = Donationdate::where('user_id' , $user_id)->get();
        return response()->json(["data" => $dates] , 200);
        }catch(Exception $e){
         return response()->json(["Message" => "Can't fetch data"] , 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['date' => 'required']);
        try{
         Donationdate::create([
           'user_id' => Auth::guard('user')->user()->id,
           'date' => $request->date
         ]);
         return response()->json(["Message" => "Donation date added successfully"] , 201);
        }catch(Exception $e){
            // return response()->json(["error" => $e]);
            return response()->json(["Message" => "Can't add donation date now , try later"] , 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Donationdate $donationdate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $donationdate = Donationdate::find($id);
        if(! is_null($donationdate)){
          return response()->json(["data" => $donationdate] , 200);
        }else{
            return response()->json(["Message" => "Object not found"] , 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $donationdate = Donationdate::find($id);
        if(! is_null($donationdate)){
            $request->validate(['date' => 'required']);
             try{
              $donationdate->date = $request->date;
              $donationdate->update();
              return response()->json(["Message" => "Donation date updated successfully"] , 200);
             }catch(Exception $e){
              return response()->json(["Message" => "Can't update donation date now , try later"] , 500);
             }
        }else{
            return response()->json(["Message" => "Object not found !"] , 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $donationdate = Donationdate::find($id);
        if(! is_null($donationdate)){
           $donationdate->delete();
           return response()->json(["Message" => "Donation date deleted successfully"] , 200);
        }else{
            return response()->json(["Message" => "Object not found !"] , 404);
        }

    }

    public function latestDate(){
        $user_id = Auth::guard('user')->user()->id;
        try{
        $date = Donationdate::where('user_id' , $user_id)->latest()->first();
        return response()->json(["data" => $date] , 200);
        }catch(Exception $e){
         return response()->json(["Message" => "Can't fetch data"] , 500);
        }
    }

    public function donationState(){
        $user_id = Auth::guard('user')->user()->id;
        $donation = Donationdate::where('user_id' , $user_id)->latest()->first();

        $date = Carbon::parse($donation->date)->format('d-m-Y H:i:s');
        $newdate = Carbon::createFromFormat('d-m-Y H:i:s' , $date)->addDays(21);
        $now = Carbon::now();

        if($now->gt($newdate)){
          return response()->json(["Message" => "You are ready to donate now"] , 200);
        }else{
        return response()->json(["Message" => "You aren't ready to donate now"] , 200);
        }

    }
}
