<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Bloodbag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class BloodbagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
        $bloodbags = Bloodbag::where('approved' , 0)->get();
        return response()->json(["data" => $bloodbags] , 200);

        }catch(Exception $e){
            return response()->json(["Message" => "Can't fetch data !"] , 500);
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
      
         $request->validate([
            'user_id' => 'required|numeric' , 
            'content' => 'required|string' , 
            'address' => 'required|string' , 
            'phone' => 'required|string|max:11' , 
            'blood_type' => 'required|string|max:4' , 
            'bag_num' => 'required|numeric'
        ]);

        Bloodbag::create([
            'user_id' => Auth::guard('user')->user()->id , 
            'content' =>  $request->content , 
            'address' =>  $request->address , 
            'phone' => $request->phone , 
            'blood_type' => $request->blood_type , 
            'bag_num' => $request->bag_num
        ]);

        return response()->json(["Message" => "Blood bags request created successfully"] , 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bloodbag $bloodbag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bloodbag = Bloodbag::find($id);
        if(! is_null($bloodbag)){
        return response()->json(["data" => $bloodbag] , 200);
    }else{
        return response()->json(["data" => "Object not found !"] , 404);

    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bloodbag $bloodbag)
    {
        $request->validate([
            'user_id' => 'required|numeric' , 
            'content' => 'required|string' , 
            'address' => 'required|string' , 
            'phone' => 'required|string|max:11' , 
            'blood_type' => 'required|string|max:4' , 
            'bag_num' => 'required|numeric'
        ]);

        $bloodbag->user_id =  Auth::guard('user')->user()->id;
        $bloodbag->content =  $request->content;
        $bloodbag->address =  $request->address;
        $bloodbag->phone = $request->phone;
        $bloodbag->blood_type = $request->blood_type;
        $bloodbag->bag_num = $request->bag_num;
        $bloodbag->update();
        return response()->json(["Message" => "Request data updated successfully"] , 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bloodbag = Bloodbag::find($id);
       if(! is_null($bloodbag)){
            $bloodbag->delete();
            return response()->json(["Message" => "Request data deleted successfully" ] , 200);
    }
       
            return response()->json(["Message" => "Object not found!" ] , 404);
    
} 

    public function userRequests(){
        $user_id = Auth::guard('user')->user()->id;
        try{
        $requests = Bloodbag::where('user_id' , $user_id)->where('approved' , 0)->get();
        return response()->json(["data" => $requests] , 200);
        }catch(Exception $e){
            return response()->json(["Message" => "Can't fetch data !"] , 500);

        }
    }

    public function approve($id){
       $bloodbag = Bloodbag::find($id);
       if(! is_null($bloodbag)){
          $bloodbag->approved = 1;
          $bloodbag->update();
          return response()->json(["Message" => "Your request approved successfully" ] , 200);
       }else{
        return response()->json(["Message" => "Object not found!" ] , 404);
       }
    }


    public function sameBloodType(){
        $blood_type = Auth::guard('user')->user()->blood_type;
        $requests = Bloodbag::where('blood_type' , $blood_type)->where('approved' , 0)->get();
        if(! is_null($requests) && count($requests) > 0){
         return response()->json(["data" => $requests] , 200);
        }else{
            return response()->json(["Message" => "There is no requests need your blood type" ] , 200);

        }
    }
 

    }

