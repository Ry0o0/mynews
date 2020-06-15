@extends('layouts.front')

@section('content')
    <div class="container">
        <hr color="#c0c0c0">
        <div class="row">
            <div class="profiles col-md-8 mx-auto mt-3">
                @foreach($profiles as $profile)
                    <div class="profile">
                        <div class="row">
                            <div class="text col-md-6">
                                <div class="date">
                                    {{ $profile->updated_at->format('Y年m月d日') }}
                                </div>
                                <div class="name">
                                    {{ str_limit($profile->name, 100) }}
                                </div>
                                <div class="gender mt-3">
                                    {{ str_limit($profile->gender, 10) }}
                                </div>
                                <div class="hobby mt-3">
                                    {{ str_limit($profile->hobby, 10) }}
                                </div>
                                <div class="introduction mt-3">
                                    {{ str_limit($profile->introduction, 500) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr color="#c0c0c0">
                @endforeach
            </div>
        </div>
    </div>
    </div>
@endsection