<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use DB;

class HomeController extends Controller
{
    use AuthorizesRequests;

    private function get_home_feed(Request $request, $admin = false)
    {
        $collection = Post::select(
            'id'
            , 'title'
            , 'image'
            , 'published'
            , 'author'
            , 'key'
        )->orderBy('published', 'DESC');
     
        if (!$admin) {
            $collection = $collection->whereDate('published', '<=', date('Y-m-d'));
        }
        
        $collection = $collection->get();
        
        return collect(['feed' => $collection->toArray()]);
    }
    
    public function get_home_web(Request $request)
    {
        $data = $this->get_home_feed($request);
        $data = $this->add_meta_data($data, $request);
        return view('app', ['data' => $data]);
    }

    public function get_home_api(Request $request)
    {
        $data = $this->get_home_feed($request);
        return response()->json($data);
    }

    public function get_admin_home_api(Request $request)
    {
        $data = $this->get_home_feed($request, true);
        return response()->json($data);
    }
}
