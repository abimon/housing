<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appartments;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AppartmentsController extends Controller
{
    public function index()
    {
        $appartments = Appartments::orderBy("created_at", "desc")->paginate(10);
        return response()->json(compact("appartments"), 200);
    }

    public function create()
    {
        //
    }

    public function store()
    {
        $validateUser = Validator::make(
            request()->all(),
            [
                'category' => 'required|string',
                'location' => 'required|string',
                'price' => 'required|string',
                'description' => 'required|string',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        if (request()->hasFile('cover')) {
            $filepath = (pathinfo(request()->file('cover')->getClientOriginalPath(), PATHINFO_FILENAME));
            $filename = (Str::slug($filepath, '_')) . '.' . (request()->file('cover')->getClientOriginalExtension());
            request()->file('cover')->storeAs('public/properties', $filename);
        } else {
            $filename = 'noimage.png';
        }
        $appartment = Appartments::create([
            "cover_path" => $filename,
            "user_id" => auth()->user()->id,
            "category" => request()->category,
            "location" => request()->location,
            "price" => request()->price,
            "description" => request()->description,
            'uniq_id' => strtoupper(uniqid())
        ]);
        return response()->json(compact("appartment"), 200);
    }

    public function show()
    {
        //
    }

    public function edit()
    {
        //
    }
    public function update($id)
    {
        $appartment = Appartments::findOrFail($id);
        if (request('owner') != null) {
            $appartment->owner = request('owner');
        }
        if (request()->hasFile('cover')) {
            $filepath = (pathinfo(request()->file('cover')->getClientOriginalPath(), PATHINFO_FILENAME));
            $filename = (Str::slug($filepath, '_')) . '.' . (request()->file('cover')->getClientOriginalExtension());
            request()->file('cover')->saveAs('public/property/cover' . $filename);
            $appartment->cover = $filename;
        }
        if (request('category') != null) {
            $appartment->category = request('category');
        }
        if (request('location') != null) {
            $appartment->location = request('location');
        }
        if (request('price') != null) {
            $appartment->price = request('price');
        }
        if (request('description') != null) {
            $appartment->description = request('description');
        }
        $appartment->update();
        return response()->json(compact("appartment"), 200);
    }

    public function destroy($id)
    {
    }
}
