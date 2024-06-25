<?php

namespace App\Http\Controllers;

use App\Models\Appartments;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AppartmentsController extends Controller
{
    public function index()
    {
        $appartments = Appartments::orderBy("created_at","desc")->paginate(10);
        return response()->json(compact("appartments"),200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validateUser = Validator::make(request()->all(), 
            [
                'cover' => 'required|image',
                'category'=>'required|string',
                'location'=>'required|string',
                'price'=>'required|string',
                'description'=>'required|string',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
        if(request()->hasFile('cover')){
            $filepath =(pathinfo(request()->file('cover')->getClientOriginalPath(), PATHINFO_FILENAME));
            $filename=(Str::slug($filepath,'_')).'.'.(request()->file('cover')->getClientOriginalExtension());
            request()->file('cover')->storeAs('public/properties',$filename);
        }
        else{
            return response()->json(['message' => 'File missing'], 404);
        }
        $appartment = Appartments::create([
            "cover_path"=>$filename,
            "user_id"=> $request->user()->id,
            "category"=> $request->category,
            "location"=> $request->location,
            "price"=> $request->price,
            "description"=> $request->description,
            'uniq_id'=>strtoupper(uniqid())
        ]);
        return response()->json(compact("appartment"),200);
    }

    public function show(Appartments $appartments)
    {
        //
    }

    public function edit(Appartments $appartments)
    {
        //
    }
    public function update($id)
    {
        $appartment = Appartments::findOrFail($id);
        if(request('owner')!=null){
            $appartment->owner=request('owner');
        }
        if(request()->hasFile('cover')){
            $filepath =(pathinfo(request()->file('cover')->getClientOriginalPath(), PATHINFO_FILENAME));
            $filename=(Str::slug($filepath,'_')).'.'.(request()->file('cover')->getClientOriginalExtension());
            request()->file('cover')->saveAs(''.$filename);
            $appartment->cover = $filename;
        }
        if(request('category')!=null){
            $appartment->category=request('category');
        }
        if(request('location')!=null){
            $appartment->location=request('location');
        }
        if(request('price')!=null){
            $appartment->price=request('price');
        }
        if(request('description')!=null){
            $appartment->description=request('description');
        }
        $appartment->update();
        return response()->json(compact("appartment"),200);
    }
    
    public function destroy($id)
    {
        
    }
}
