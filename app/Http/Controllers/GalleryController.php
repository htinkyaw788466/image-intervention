<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    protected $limited=6;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries=Gallery::latest()->simplePaginate($this->limited);
        return view('gallery.index',compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());

        //image upload one way
        //$request->file('image')->move(public_path('image'),'a.jpg');

        //image upload  two way with time
        // $currentTime=time().'_'.$request->file('image')->getClientOriginalName();
        // $request->file('image')->move(public_path('image'),$currentTime);

        //image upload  three way in storage
        //$request->file('image')->store('upload');

        //image upload  four way in storage with time
        // $currentTime=time().'_'.$request->file('image')->getClientOriginalName();
        // $request->file('image')->storeAs('upload',$currentTime);

        //image upload five way with array
        // if($request->hasFile('image')){
        //     foreach($request->file('image') as $image){
        //         $currentTime=time().'_'.$image->getClientOriginalName();
        //         $image->storeAs('upload',$currentTime);

        //         $gallery=new Gallery();
        //         $gallery->name=$currentTime;
        //         $gallery->save();

        //     }
        // }
        // return redirect()->route('main-home')->with('status','image upload successfully');

        //image upload six way with image intervention
        $this->validate($request, [
            'image' => 'required|mimes:png,jpg,jpeg',
        ]);

        // Get Form Image
        $image = $request->file('image');
        if (isset($image)) {

            // Make Unique Name for Image
            $currentDate = Carbon::now()->toDateString();
            $imageName = $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Check gallery Dir is exists
            if (!Storage::disk('public')->exists('galleries')) {
                Storage::disk('public')->makeDirectory('galleries');
            }

            // Resize Image for gallery and upload
            $galleryImage = Image::make($image)->resize(740,925)->stream();
            Storage::disk('public')->put('galleries/' . $imageName, $galleryImage);
        } else {
            $imagename = 'https://source.unsplash.com/random';
        }

        $gallery = new Gallery();
        $gallery->name = $imageName;
        $gallery->save();
        return redirect()->route('main-home')->with('status', 'image upload successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery=Gallery::findOrFail($id);
        //delete old image
        if (Storage::disk('public')->exists('galleries/'.$gallery->name)) {
            Storage::disk('public')->delete('galleries/'.$gallery->name);
           }
        $gallery->delete();
        return redirect()->route('main-home')->with('successMsg','gallery deleted successfully');
    }

    public function download($id)
    {
        $gallery=Gallery::findOrFail($id);
        return Storage::download('public/galleries/'.$gallery->name);
    }
}
