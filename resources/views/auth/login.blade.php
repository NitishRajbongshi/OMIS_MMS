@extends('layouts.index')

@section('content')
    <?php $brdcstMsg = Cache::get('global_message'); ?>

    <main class="oamis-login">
        <nav class="oamis-login-nav">
            <a class="oamis-login-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/main_logo.png') }}" alt="Nagaland PWD logo">
                <span>
                    <strong>OMIS Nagaland PWD</strong>
                    <small>Online Management and Information System</small>
                </span>
            </a>
            <div class="oamis-login-actions">
                <button type="button" class="oamis-theme-toggle" data-theme-toggle aria-pressed="false">
                    <i class="fas fa-moon"></i><span>Dark</span>
                </button>
                <a href="{{ url('/') }}" class="btn btn-light">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </div>
        </nav>

        @if ($brdcstMsg)
            <div class="oamis-login-alert" role="status">
                <i class="fa fa-bullhorn"></i>
                <div class="oamis-login-alert-track">
                    <span>{{ $brdcstMsg }}</span>
                </div>
            </div>
        @endif

        <section class="oamis-login-shell">
            <aside class="oamis-login-panel">
                <span class="oamis-login-eyebrow">Secure departmental access</span>
                <h1>Infrastructure asset intelligence for Nagaland PWD</h1>
                <p>
                    Access road, building, national highway and mechanical asset workflows from one
                    unified government information system.
                </p>

                <div class="oamis-login-metrics" aria-label="System modules">
                    <span><i class="fa fa-road"></i>Roads & Bridges</span>
                    <span><i class="fa fa-building"></i>Housing Assets</span>
                    <span><i class="fa fa-route"></i>National Highways</span>
                    <span><i class="fa fa-gears"></i>Mechanical Assets</span>
                </div>
            </aside>

            <section class="oamis-login-card" aria-labelledby="login-title">
                <div class="oamis-login-card-header">
                    <span class="oamis-login-mark"><i class="fa fa-shield-halved"></i></span>
                    <div>
                        <h2 id="login-title">Sign in to OAMIS</h2>
                        <p>Verify your department and account details to continue.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}" autocomplete="off" class="oamis-login-form">
                    @csrf

                    <div class="form-group">
                        <label for="department">Department <span class="text-danger">*</span></label>
                        <div class="oamis-login-field">
                            <i class="fa fa-building-columns"></i>
                            <select id="department" name="department"
                                class="form-select @error('department') is-invalid @enderror department">
                                <option value="" selected disabled>Select Department</option>
                                <option value="14">Admin</option>
                                @foreach ($deptname as $d)
                                    <option value="{{ $d->id }}">{{ $d->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('department')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email ID <span class="text-danger">*</span></label>
                        <div class="oamis-login-field">
                            <i class="fa fa-envelope" id="email-icon"></i>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror email"
                                placeholder="Enter email address" autocomplete="off"
                                oninput="this.value = this.value.toLowerCase()" value="{{ old('email') }}">
                        </div>
                        <span class="spanHide text-xs text-danger"></span>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <div class="oamis-login-field">
                            <i class="fa fa-lock"></i>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter password">
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="captcha">Security Code <span class="text-danger">*</span></label>
                        <div class="oamis-login-captcha">
                            <div class="oamis-login-field">
                                <i class="fa fa-key"></i>
                                <input type="text" id="captcha" name="captcha"
                                    class="form-control @error('captcha') is-invalid @enderror"
                                    placeholder="Enter code" autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="captcha-img-group d-flex gap-1 align-items-center">
                                <img src="{{ route('captcha.image') }}" id="captcha-img" alt="Security Code" class="rounded" style="height: 46px; width: 130px; object-fit: cover; border: 1px solid var(--oamis-border); background-color: var(--oamis-soft);">
                                <button type="button" class="btn btn-outline-secondary p-0 d-flex align-items-center justify-content-center" id="reload-captcha" title="Reload security code" style="height: 46px; width: 46px; border-radius: 12px;">
                                    <i class="fa fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        @error('captcha')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="oamis-login-links">
                        <a href="{{ URL::temporarySignedRoute('forgotPassword', now()->addMinutes(5)) }}">
                            <i class="fa fa-circle-question"></i>
                            <span>Forgot password?</span>
                        </a>
                    </div>

                    <button type="submit" class="btn btn-primary oamis-login-submit loginBtnn">
                        <i class="fa fa-right-to-bracket"></i>
                        <span>Login</span>
                    </button>
                </form>
            </section>
        </section>
    </main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/oamis-login.css') }}">
@endpush

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            let xhr = null;

            const emailInput = $('.email');
            const department = $('.department');
            const spanHide = $('.spanHide');
            const emailIcon = $('#email-icon');
            const emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            function resetMessage() {
                spanHide.empty().removeClass('text-danger text-success');
            }

            function setEmailLoadingState(isLoading) {
                if (isLoading) {
                    emailIcon.removeClass('fa-envelope').addClass('fa-spinner fa-spin');
                } else {
                    emailIcon.removeClass('fa-spinner fa-spin').addClass('fa-envelope');
                }
            }

            function checkUserAvailability() {
                const email = emailInput.val().trim();
                const dept = department.val();

                resetMessage();

                if (!email || !dept) return;

                if (!emailReg.test(email)) {
                    spanHide.html('Please enter a valid email address.').addClass('text-danger');
                    return;
                }

                if (xhr && xhr.readyState !== 4) {
                    xhr.abort();
                }

                setEmailLoadingState(true);
                xhr = $.ajax({
                    method: "GET",
                    url: "{{ route('checkUserActive') }}",
                    data: {
                        department: dept,
                        email: email
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            if (response.message === 'active') {
                                resetMessage();
                            } else {
                                spanHide.html('Account unavailable or deactivated.').addClass('text-danger');
                            }
                        } else {
                            spanHide.html(response.message || 'Error checking account.').addClass('text-danger');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.statusText === 'abort') return;
                        spanHide.html('Could not verify account status.').addClass('text-danger');
                    },
                    complete: function() {
                        setEmailLoadingState(false);
                    }
                });
            }

            department.on('change', function() {
                if (!emailInput.val().trim()) return;
                checkUserAvailability();
            });

            emailInput.on('blur', function() {
                checkUserAvailability();
            });

            // Reload CAPTCHA image
            $('#reload-captcha').on('click', function() {
                $('#captcha-img').attr('src', "{{ route('captcha.image') }}?t=" + new Date().getTime());
            });
        });

        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('copy', e => e.preventDefault());
    </script>
@endpush
