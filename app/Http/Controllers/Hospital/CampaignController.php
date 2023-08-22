<?php

namespace App\Http\Controllers\Hospital;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hospital_id = Auth::guard('hospital')->user()->id;
        try{
        $campaigns = Campaign::where('hospital_id' , $hospital_id)->where('end' , 0)->get();
        return response()->json(["data" => $campaigns] , 200);
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
            'name' => 'required|string',
            'address' => 'required|string',
            'date' => 'required',
        ]);

        try{
        Campaign::create([
            'hospital_id' => Auth::guard('hospital')->user()->id,
            'name' => $request->name,
            'address' => $request->address,
            'date' => $request->date
        ]);
       
        return response()->json(["Message" => "Campaign created successfully"] , 201);

    }catch(Exception $e){
        return response()->json(["Message" => "Can't create campaign now , try later"] , 500);
    }
    }
    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $campaign = Campaign::find($id);
        if(! is_null($campaign)){
           return response()->json(["data" => $campaign] , 200);
        }else{
            return response()->json(["Message" => "Object not found !"] , 404);
 
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        if(! is_null($campaign)){
            $request->validate([
                'name' => 'required|string',
                'address' => 'required|string',
                'date' => 'required',
            ]);
            $campaign->name = $request->name;
            $campaign->address = $request->address;
            $campaign->date = $request->date;
            $campaign->update();
           return response()->json( ["Message" => "Campaign data updated successfully"] , 200);
        }else{
            return response()->json(["Message" => "Object not found !"] , 404);
 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $campaign = Campaign::find($id);
        if(! is_null($campaign)){
            $campaign->delete();
            return response()->json(["Message" => "Campaign data deleted successfully"] , 200);
        }else{
            return response()->json(["Message" => "Object not found !"] , 404);

        }
    }

    public function end($id){
        $campaign = Campaign::find($id);
        if(! is_null($campaign)){
           $campaign->end = 1;
           $campaign->update();
           return response()->json(["Message" => "Campaign ended successfully"] , 200);

        }else{
         return response()->json(["Message" => "Object not found !"] , 404);
        }
    }
}
