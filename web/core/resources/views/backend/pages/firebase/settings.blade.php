@extends('backend.admin-master')
@section('site-title')
    {{__('Firebase Settings')}}
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-6 col-lg-6 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="header-wrap d-flex justify-content-between mb-3">
                    <div class="left-content">
                        <h4 class="header-title">{{__('Firebase Settings')}}   </h4>
                    </div>
                </div>
                <x-validation.error/>

                @if($firebaseFileExists)
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i> {{ __('Firebase JSON file is already uploaded.') }}
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-circle"></i> {{ __('No Firebase JSON file uploaded yet. Please upload a new one.') }}
                    </div>
                @endif

                <form action="{{route('admin.firebase.settings')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form__input__flex">
                        <div class="form__input__single">
                            <label for="firebase_json" class="form__input__single__label">{{__('Upload Firebase JSON File')}}</label>
                            <input class="form__control radius-5" type="file" name="firebase_json" id="firebase_json" accept=".json" required>
                        </div>
                    </div>
                    <div class="btn_wrapper mt-4">
                        <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        <x-icon.icon-picker/>
    </script>
@endsection
