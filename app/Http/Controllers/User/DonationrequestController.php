<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Donationrequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationrequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
          $requests = Donationrequest::where('approved' , 0)->get();
          return response()->json(["data" => $requests] , 200); 
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
        $user_id = Auth::guard('user')->user()->id;
        $lastDonation = Donationrequest::where('user_id' , $user_id)->latest()->first();
        if(! is_null($lastDonation) && $lastDonation->approved == 0){
            return response()->json(["Message" => "Your last request isn't approved yet"] , 200);
        }
       $request->validate([
         'content' => 'required|string'
       ]);   

       try{
         Donationrequest::create([
            'user_id' => $user_id,
            'content' => $request->content
         ]);
         return response()->json(["Message" => "Donation request created successfully"] , 201);
       }catch(Exception $e){
        return response()->json(["Message" => "Can't create donation request now , try later"] ,500);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(Donationrequest $donationrequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $donationrequest = Donationrequest::find($id);
        if(! is_null($donationrequest)){
        return response()->json(["data" => $donationrequest] , 200);
    }else{
        return response()->json(["Message" => "Object not found !"] , 404);

    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $donationrequest = Donationrequest::find($id);
        if(! is_null($donationrequest)){
            $request->validate(['content' => 'required|string']);
            $donationrequest->content = $request->content;
            $donationrequest->update();
            return response()->json(["Message" => "Request data updated successfully"] , 200);
        }else{
            return response()->json(["Message" => "Object not found !"] , 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $donationrequest = Donationrequest::find($id);
        if(! is_null($donationrequest)){
            $donationrequest->delete();
            return response()->json(["Message" => "Request data deleted successfully"] , 200);
        }else{
            return response()->json(["Message" => "Object not found !"] , 404);
        }
    }

    public function userRequests(){
        $user_id = Auth::guard('user')->user()->id;
        try{
         $requests = Donationrequest::where('user_id' , $user_id)->where('approved' , 0)->get();
         return response()->json(["data" => $requests] , 200);
        }catch(Exception $e){
         return response()->json(["Message" => "can't fetch data"] , 500);
        }
    }

    public function approve($id){
        $donationRequest = Donationrequest::find($id);
        if(! is_null($donationRequest)){
           $donationRequest->approved = 1;
           $donationRequest->update();
           return response()->json(["Message" => "Your request approved successfully" ] , 200);
        }else{
         return response()->json(["Message" => "Object not found!" ] , 404);
        }
     }
}
