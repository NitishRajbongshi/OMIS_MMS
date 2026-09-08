@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        {{-- <li class="breadcrumb-item"><a href="{{ route('viewUsers') }}">View Users</a></li> --}}
                        <li class="breadcrumb-item">User history </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <!-- table content -->
        <div>
            <p class="border p-2 text-light" style="background: #417DBE;">Showing user log for
                <span class="text-mg text-bold">
                    {{ $userName }}
                </span>
            </p>
        </div>
        <div class="d-flex">
            <button class="border-0 bg-primary px-4 py-1">
                <a href="{{ URL::temporarySignedRoute('userMovement', now()->addMinutes(60), ['id' => $userId, 'user' => session('userName')]) }}"
                    class="text-light">Officiating Records</a>
            </button>
            <button class="border-0 bg-secondary px-4 py-1">
                <a href="{{ URL::temporarySignedRoute('userMovementTemp', now()->addMinutes(60), ['id' => $userId, 'user' => session('userName')]) }}"
                    class="text-light">Current Records</a>
            </button>
        </div>
        <div class="container-fluid border mainBody">
            <div class="container-fluid mt-3">
                @if ($userMovements->count() === 0)
                    <p class="text-danger"><i class="fa-solid fa-notdef text-danger"></i> User does not have previous
                        records.</p>
                @else
                    <span class="mis-btn-office"></span>
                    <table class="table-responsive text-xs table table-bordered table-striped user_list" id="user_table">
                        <thead class="theader text-white" style="background-color:#417DBE">
                            <th class="text-center" style="min-width: 4rem;">Sl. No</th>
                            <th class="text-center" style="min-width: 8rem;">Department</th>
                            <th class="text-center" style="min-width: 8rem;">Designation</th>
                            <th class="text-center" style="min-width: 8rem;">Office Type</th>
                            <th class="text-center" style="min-width: 8rem;">Office</th>
                            <th class="text-center" style="min-width: 8rem;">From</th>
                            <th class="text-center" style="min-width: 8rem;">To</th>
                            <th class="text-center" style="min-width: 8rem;">Reason</th>
                        </thead>

                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($userMovements as $userMovement)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td>{{ $userMovement->department_name }}</td>
                                    <td>{{ $userMovement->desg_name }}</td>
                                    <td>{{ $userMovement->office_type_desc }}</td>
                                    <td>{{ $userMovement->office_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($userMovement->assign_from)->format('d-m-Y') }}</td>
                                    <td>{{ $userMovement->assign_to ? \Carbon\Carbon::parse($userMovement->assign_to)->format('d-m-Y') : 'Present' }}
                                    </td>
                                    <td>{{ $userMovement->reason }}</td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
@endpush

@push('scripts')
    <script>
        $(function() {
            $("#user_table")
                .DataTable({
                    buttons: ["csv", "excel"],
                })
                .buttons()
                .container()
                .appendTo(".mis-btn-office");
        });
        $(".modalClose").on("click", function() {
            location.reload();
        });
    </script>
@endpush
