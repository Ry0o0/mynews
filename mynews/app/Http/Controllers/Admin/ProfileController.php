<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;
use App\ProfileHistory;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        //バリテーションをする。
        $this->validate($request, Profile::$rules);

        //Profileモデルを取得する。
        $profile = new Profile;

        //リクエストを取得して保存する。
        $form = $request->all();
        $profile->fill($form)->save();

        unset($form['_token']);

        return redirect('admin/profile/create');
    }

    public function edit(Request $request)
    {
        //該当のProfileモデルを取得し、ない場合404を表示する。
        $profile = Profile::find($request->id);
        if (empty($profile)) {
          abort(404);
        }
        return view('admin.profile.edit', ['profile_form' => $profile]);
    }

    public function update(Request $request)
    {
        //バリテーションをする。
        $this->validate($request, profile::$rules);

        //該当のProfileモデルを取得する。
        $profile = Profile::find($request->id);

        //リクエストを取得し、保存する。
        $profile_form = $request->all();
        $profile->fill($profile_form)->save();
        unset($profile_form['_token']);

        //ProfileHistoryモデルを取得して、履歴を保存する。
        $profile_history = new ProfileHistory;
        $profile_history->profile_id = $profile->id;
        $profile_history->edited_at = Carbon::now();
        $profile_history->save();

        return redirect('/profile');
    }
}
