<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\HTML;

use App\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        //newsを投稿日時順に並べる。
        $posts = News::all()->sortByDesc('updated_at');

        //投稿がある場合、最新の投稿を取り出す。
        if (count($posts) > 0) {
            $headline = $posts->shift();
        } else {
            $headline = null;
        }

        return view('news.index', ['headline' => $headline, 'posts' => $posts]);
    }
}
