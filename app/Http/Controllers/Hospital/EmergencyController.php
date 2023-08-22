<?php

namespace App\Http\Controllers\Hospital;
use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hospital_id = Auth::guard('hospital')->user()->id;
        try{
        $emergencies = Emergency::where('hospital_id' , $hospital_id)->where('end' , 0)->get();
        return response()->json(["data" => $emergencies] , 200);
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
        $request->validate([
            'content'=>'required|string',	
            'blood_type' => 'required|string'
        ]);

        try{
         Emergency::create([
            'hospital_id' => Auth::guard('hospital')->user()->id,
            'content' => $request->content,
            'blood_type' => $request->blood_type
         ]);
         return response()->json(["Message" => "Emergency case added successfully"] , 200);
        }catch(Exception $e){
            return response()->json(["Message" => "Can't add emergency case now , try later"] , 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Emergency $emergency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $emergency = Emergency::find($id);
        if(! is_null($emergency)){
          return response()->json(["data" => $emergency] , 200);
        }else{
            return response()->json(["Message" => "Object not found !"] , 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $emergency = Emergency::find($id);

        if(! is_null($emergency)){

            $request->validate([
                'content'=>'required|string',	
                'blood_type' => 'required|string'
            ]);

            $emergency->content = $request->content;
            $emergency->blood_type = $request->blood_type;
            $emergency->update();
            return response()->json(["Message" => "Emergency Case updated successfully"] , 200);
        }else{
            return response()->json(["Message" => "Object not found !"] , 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $emergency = Emergency::find($id);
        if(! is_null($emergency)){
          $emergency->delete();
          return response()->json(["Message" => "Emergency deleted successfully"] , 200);

        }else{
            return response()->json(["Message" => "Object not found !"] , 404);

        }
    }

    public function end($id){
        $emergency = Emergency::find($id);
        if(! is_null($emergency)){
           $emergency->end = 1;
           $emergency->update();
           return response()->json(["Message" => "Emergency ended successfully"] , 200);

        }else{
            return response()->json(["Message" => "Object not found !"] , 404);

        }
    }
}
