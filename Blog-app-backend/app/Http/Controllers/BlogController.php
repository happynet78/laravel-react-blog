<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\TempImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    // This method will return all blogs.
    public function index(Request $request) {
        DB::enableQueryLog();
        $blogs = Blog::orderBy('created_at', 'desc');

        if(!empty($request->keyword)) {
            $blogs = $blogs->where('title', 'like', '%' . $request->keyword . '%');
        }

        $blogs = $blogs->get();

        return response()->json([
            'status' =>  true,
            'data' =>  $blogs
        ]);
    }

    // This method will return a single blog
    public function show($id) {
        $blog = Blog::find($id);

        if(is_null($blog)) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found'
            ]);
        }

        $blog['date'] = Carbon::parse($blog->created_at)->format('d M, Y');

        return response()->json([
            'status' =>  true,
            'data' =>  $blog
        ]);
    }

    // This method will store a blog
    public function store(Request $request) {
        DB::enableQueryLog();
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:10',
            'author' => 'required|min:3'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator->errors()
            ]);
        }

        $blog = new Blog();
        $blog->title  = $request->title;
        $blog->author = $request->author;
        $blog->description = $request->description;
        $blog->shortDesc = $request->shortDesc;
        $blog->save();
//dump(DB::getQueryLog());
        // Save Image Here
        $tempImage = TempImage::find($request->image_id);

        if($tempImage) {
            $imageExtArray = explode('.', $tempImage->name);
            $ext = last($imageExtArray);
            $imageName = time().'_'. $blog->id .'.' . $ext;

            $blog->image = $imageName;
            $blog->save();

            $sourcePath = public_path('uploads/temp/'. $tempImage->name);
            $descPath = public_path('uploads/blogs/'. $imageName);
            File::copy($sourcePath, $descPath);

            // Temp image delete
            File::delete(public_path('uploads/temp/'. $tempImage->name));
        }

        return response()->json([
            'status' => true,
            'message' => 'Blog added successfully.',
            'data' => $blog
        ]);
    }

    // This method will update a blog
    public function update($id, Request $request) {

        $blogs = Blog::find($id);

        if(is_null($blogs)) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:10',
            'author' => 'required|min:3'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator->errors()
            ]);
        }


        $blogs->title  = $request->title;
        $blogs->author = $request->author;
        $blogs->description = $request->description;
        $blogs->shortDesc = $request->shortDesc;
        $blogs->save();

        // Save Image Here
        $tempImage = TempImage::find($request->image_id);

        if($tempImage) {

            // Delete old image here
            if(!is_null($blogs->image)) {
                File::delete(public_path('uploads/blogs/'. $blogs->image));
            }

            $imageExtArray = explode('.', $tempImage->name);
            $ext = last($imageExtArray);
            $imageName = time().'_'. $blogs->id .'.' . $ext;

            $blogs->image = $imageName;
            $blogs->save();

            $sourcePath = public_path('uploads/temp/'. $tempImage->name);
            $descPath = public_path('uploads/blogs/'. $imageName);
            File::copy($sourcePath, $descPath);

            // Temp image delete
            File::delete(public_path('uploads/temp/'. $tempImage->name));
        }

        return response()->json([
            'status' => true,
            'message' => 'Blog updated successfully.',
            'data' => $blogs
        ]);
    }

    // This method will delete a blog
    public function destroy($id) {
        $blog = BLog::find($id);

        if(is_null($blog)) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found'
            ]);
        }

        // Delete blog image first
        if(!is_null($blog->image)) {
            File::delete(public_path('uploads/blogs/'. $blog->image));
        }

        // Delete blog from DB
        $blog->delete();

        return response()->json([
            'status' => true,
            'message' => 'Blog deleted successfully.'
        ]);
    }
}
