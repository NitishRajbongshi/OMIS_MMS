@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid text-sm">
            <div class="row px-2">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">Reset Password</li>
                </ol>
            </div>
        </div>
        <div class="mainBody">
            @if (session('failed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Failed!</strong> {{ session('failed') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            {{-- Reset Password --}}
            <div class="row p-1">
                <div class="col-md-4 col-sm-12">
                    <div class="border p-2 rounded" style="background-color: rgb(255, 250, 243)">
                        <h5 class="text-center text-warning border-bottom pb-1">
                            Reset Password
                        </h5>
                        <form action="/reset-password" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="code"><i class="fa-solid fa-lock"></i>
                                    Secret Code</label>
                                <input type="password" class="form-control" id="code" aria-describedby="emailHelp"
                                    name="code" placeholder="Enter Code">
                                <small id="emailHelp" class="form-text text-muted">
                                    If you haven't set the code yet, click <a class="text-bold text-primary"
                                        href="{{ URL::temporarySignedRoute('secretCode', now()->addMinutes(5), ['user' => session('userName')]) }}">here</a>
                                </small>
                                @error('code')
                                    <div class="text-danger text-xs ml-1">
                                        <i class="fa-solid fa-circle-info"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="fa-solid fa-lock"></i>
                                    New Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter Password" aria-describedby="passwordHelp">
                                <small id="passwordHelp" class="form-text text-muted">
                                    Password should contains minimum 8-characters.
                                </small>
                                @error('password')
                                    <div class="text-danger text-xs ml-1">
                                        <i class="fa-solid fa-circle-info"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-md btn-warning">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12"></div>
                <div class="col-md-4 col-sm-12"></div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .card {
            font-family: 'Times New Roman', serif;
            font-weight: normal;
        }

        .theader {
            background-color: #073D36;
            color: white;
        }

        .modal-header {
            background-image: linear-gradient(to right, #102E09, #1B6B09);
            color: white;
        }

        #viewBody {
            background-image: linear-gradient(#8be5f9, #E1F4F9);
            border-radius: 5px;
        }

        #cardHead {
            background-color: #23783B;
        }
    </style>
@endpush
