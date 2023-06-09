<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request()->get('per-page');
        $post = Post::paginate($perPage);
        $postCollection = PostResource::collection($post);

        $postData['data'] = $postCollection;
        $postData['next_page_url'] = $post->nextPageUrl();

        return response()->json([
            'data' => $postData
        ], 200);
    }

    public function showById($id)
    {
        return Post::where('id', $id)->first();
    }

    public function search(){
        $post = new Post;

        $keyword = request("search");

        $search = $post->where('judul', 'LIKE', "%{$keyword}%");

        if(!$search){
            return response()->json([
                'data' => 'artikel tidak ada'
            ], 403);
        }

        return response()->json([
            'data' => $search
        ], 200);

    }

}
