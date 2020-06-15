<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\News;
use App\History;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function add()
    {
        return view('admin.news.create');
    }

    public function create(Request $request)
    {
        $this->validate($request, News::$rules);

        $news = new News;
        $form = $request->all();

        if (isset($form['image'])) {
          $path = $request->file('image')->store('public/image');
          $news->image_path = basename($path);
        } else {
            $news->image_path = null;
        }

        unset($form['_token']);
        unset($form['image']);

        $news->fill($form);
        $news->save();

        return redirect('admin/news/create');
    }

    public function index(Request $request)
    {
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
            $posts = News::where('title', $cond_title)->get();
        } else {
            $posts = News::all();
        }
        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }

    public function edit(Request $request)
    {
        // Newsモデルからデータを取得する
        $news = News::find($request->id);
        if (empty($news)) {
          abort(404);
        }
        return view('admin.news.edit', ['news_form' => $news]);
    }


    public function update(Request $request)
    {
        //バリテーションを行う。
        $this->validate($request, News::$rules);

        //該当newsモデルを取得する。
        $news = News::find($request->id);

        //リクエストを取得する。
        $news_form = $request->all();

        //フォームに画像があれば保存する。
        if (isset($news_form['image'])) {
          $path = $request->file('image')->store('public/image');
          $news->image_path = basename($path);
          unset($news_form['image']);
        } elseif (isset($request->remove)) {
          $news->image_path = null;
          unset($news_form['remove']);
        }
        unset($news_form['_token']);

        // 該当するデータを上書きして保存する
        $news->fill($news_form)->save();

        //Historyモデルを取得して、編集履歴を保存する。
        $history = new History;
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();

        return redirect('admin/news');
    }

    public function delete(Request $request)
    {
        // 該当するNewsモデルを取得する。
        $news = News::find($request->id);
        // 削除する
        $news->delete();
        return redirect('admin/news/');
    }
}