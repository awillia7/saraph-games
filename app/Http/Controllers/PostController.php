<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    private function get_post($post, $admin = false)
    {
        $model = Post::where('key', '=', $post);

        if (!$admin)
        {
            $model->whereDate('published', '<=', date('Y-m-d'));
        }

        $model = $model->get()->toArray();

        return collect(['post' => $model]);
    }

    public function get_post_web($post, Request $request)
    {
        $data = $this->get_post($post);
        $data = $this->add_meta_data($data, $request);
        return view('app', ['data' => $data]);
    }

    public function get_post_api($post)
    {
        $data = $this->get_post($post);
        return response()->json($data);
    }

    public function post_post_api(Request $request)
    {
        $post = new Post();
        
        $post->key = $request->input('key');
        $post->title = $request->input('title');
        $post->image = $request->input('image');
        $post->content = $request->input('content');
        $post->published = $request->input('published');
        $post->author = $request->input('author');
        
        $post->save();

        return response()->json($post->toArray());
        //return response($post->jsonSerialize(), Rsponse::HTTP_CREATED);
    }

    public function get_admin_post_api($post = null)
    {
        $data = $this->get_post($post, true);
        return response()->json($data);
    }
}
