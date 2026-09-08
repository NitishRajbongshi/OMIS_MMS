@extends('layouts.index')
@section('content')
    <div class="container-fluid" style="background-image:linear-gradient(to right, #141f76, #6773f5, #6773f5, #141f76)">
        <div class="row" style="padding:8px">
            <div class="col-md-1 col-sm-2 text-center" style="">
                <img class="mainLogo" src="{{ asset('images/main_logo.png') }}" alt="logo" height="100%" width="100%"
                    style="padding-top:6%;padding-bottom:6%; border-radius: 100%;">
            </div>
            <div class="col-md-7 col-sm-6 text-white" style="padding: 1% 0% 0% 2%; font-weight:900">
                <span style="font-size:24px">ONLINE MANAGEMENT AND INFORMATION  SYSTEM</span><br>
                <span style="font-size:14px">NAGALAND PWD</span><br>
                <span style="font-size:12px">Government of Nagaland</span>
            </div>
        </div>
    </div>
    <section class="mt-3">
        <div class="container-fluid">
            <div class="d-flex justify-content-center">
                <div class="px-4 py-2 border border-info rounded" style="background-color: rgb(244, 244, 244); width: 26rem;">
                    <div class="text-center border-bottom my-2">
                        <h3>Forgot Password?</h3>
                        <span class="text-xs text-secondary">Create new password using secret code</span>
                    </div>
                    @if (session('failed'))
                        <div class="text-sm alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fa fa-info-circle"></i></strong> {{ session('failed') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="fa fa-check-circle"></i></strong> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('forgotPassword') }}" autocomplete="off" class="text-sm">
                        @csrf
                        <div class="form-outline mb-3">
                            <label class="form-label" for="code">
                                Email Id
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Enter email id" autocomplete="off" />
                            @error('email')
                                <div class="text-danger text-xs ml-1">
                                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-outline mb-3">
                            <label class="form-label" for="code">
                                Secret Code
                                <span class="text-danger ml-1">*</span>
                                <span class="" data-toggle="tooltip" data-placement="right"
                                    title="Code generated for reset password">
                                    <i class="fa fa-info-circle text-sm text-info"></i>
                                </span>
                            </label>
                            <input type="password" id="code" name="code" class="form-control"
                                placeholder="Enter secret code" autocomplete="off" />
                            @error('code')
                                <div class="text-danger text-xs ml-1">
                                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-outline mb-3">
                            <label class="form-label" for="password">New Password<span
                                    class="text-danger ml-1">*</span></label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Enter new password" />
                            @error('password')
                                <div class="text-danger text-xs ml-1">
                                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-outline mb-3">
                            <label class="password_confirmation" for="password">Confirm Password<span
                                    class="text-danger ml-1">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control form-control" placeholder="Re-enter the password" />
                            @error('password_confirmation')
                                <div class="text-danger text-xs ml-1">
                                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row justify-content-between align-items-center my-3">
                            <div class="col-6">
                                <a href="{{ url('/login') }}" style="width: 100%;"
                                    class="btn btn-outline-danger btn-md px-3">
                                    <i class="fa fa-backward" aria-hidden="true"></i>
                                    Login
                                </a>
                            </div>
                            <div class="col-6">
                                <button type="submit" style="width: 100%;" class="btn btn-outline-primary btn-md">
                                    <i class="fa fa-check-circle mr-1" aria-hidden="true"></i>
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('styles')
@endpush
@push('scripts')
@endpush
