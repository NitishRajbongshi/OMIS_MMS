@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid text-sm">
            <div class="row px-2">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><i class="fas fa-house mr-1"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active">Create Secret Code</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <div class="d-flex">
                    <div class="oamis-login-mark bg-light text-primary mr-3 rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px; font-size: 18px;">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div>
                        <h3 class="card-title font-weight-bold mb-0 text-lg">Create Secret Code</h3>
                        <p class="text-muted text-xs mb-0">Set your personalized secondary security code</p>
                    </div>
                </div>
                <div>
                    <a class="btn btn-sm btn-light" href="{{ route('home') }}">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if (session('failed'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3"
                        role="alert">
                        <i class="fa-solid fa-triangle-exclamation mr-3" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Failed!</strong> {{ session('failed') }}
                        </div>
                        <button type="button" class="close ml-auto text-white" data-dismiss="alert" aria-label="Close"
                            style="background: transparent !important; border: 0 !important; min-height: auto; width: auto; font-size: 1.5rem;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3"
                        role="alert">
                        <i class="fa-solid fa-circle-check mr-3" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Success!</strong> {{ session('success') }}
                        </div>
                        <button type="button" class="close ml-auto text-white" data-dismiss="alert" aria-label="Close"
                            style="background: transparent !important; border: 0 !important; min-height: auto; width: auto; font-size: 1.5rem;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Important Notice Callout -->
                <div class="callout callout-warning p-4 mb-4"
                    style="background-color: var(--oamis-soft); border-left: 4px solid var(--oamis-warning); border-radius: 12px;">
                    <h5 class="text-warning font-weight-bold text-sm mb-3">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>Important Security Instructions
                    </h5>
                    <div class="text-muted text-xs">
                        <ul class="pl-3 mb-0" style="line-height: 1.6;">
                            <li class="mb-2"><strong>Single Generation:</strong> Each user is allowed to generate a secret
                                code only once.</li>
                            <li class="mb-2"><strong>Purpose:</strong> This secret code serves the purpose of resetting
                                the password and creating a new one.</li>
                            <li class="mb-2"><strong>Confidentiality:</strong> The secret code remains hidden within the
                                application and is not visible anywhere. It is crucial to remember or securely document it
                                for future use.</li>
                            <li class="mb-2"><strong>Complexity:</strong> The code can consist of digits, numbers, or
                                special characters and must be a minimum of 5 characters.</li>
                            <li class="mb-2"><strong>Validity:</strong> The generated link for utilizing the secret code
                                becomes invalid after 5 minutes.</li>
                        </ul>
                    </div>
                </div>

                <!-- Secret Code Form -->
                <form id="secretCodeForm" action="{{ route('secretCode') }}" method="POST" autocomplete="off"
                    class="mt-4">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="secretCode" class="text-xs font-weight-bold text-uppercase tracking-wider">
                            Enter your secret code
                        </label>
                        <div class="position-relative">
                            <span class="position-absolute"
                                style="left: 14px; top: 50%; transform: translateY(-50%); color: var(--oamis-muted); z-index: 5;">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" class="form-control @error('secretCode') is-invalid @enderror"
                                id="secretCode" name="secretCode" placeholder="••••••••" required
                                style="padding-left: 40px !important; padding-right: 40px !important;">
                            <button type="button" class="btn btn-link position-absolute p-0 text-muted" id="togglePassword"
                                style="right: 14px; top: 50%; transform: translateY(-50%); z-index: 5; text-decoration: none; border: 0 !important; background: transparent !important; min-height: auto; width: auto;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        @error('secretCode')
                            <div class="text-danger text-xs mt-2 ml-1">
                                <i class="fa-solid fa-circle-info mr-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a class="btn btn-light mr-2" href="{{ asset('home') }}">
                            <span>Cancel</span>
                        </a>
                        <button type="submit" id="submitSecretCode" class="btn btn-primary px-4">
                            <i class="fa-solid fa-shield-halved mr-1"></i>
                            <span>Confirm & Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#secretCode');
                const icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@endpush
