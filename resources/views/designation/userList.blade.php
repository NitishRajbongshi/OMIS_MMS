@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('userMIS') }}">User MIS</a></li>
                        <li class="breadcrumb-item">User List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <!-- table content -->
        <div class="container-fluid border mainBody">
            <div class="container-fluid mt-3">
                @if ($userLists->count() === 0)
                    <p class="text-danger"><i class="fa-solid fa-notdef text-danger"></i> User history not found.</p>
                @else
                    <div>
                        <p class="border p-2 text-light" style="background: #417DBE;">List Of Users for Designation:
                            <span class="text-mg text-bold">
                                {{ $desgName }}
                            </span>
                        </p>
                    </div>
                    <span class="mis-btn-office"></span>
                    <table class="table-responsive text-xs table table-bordered table-striped user_list" id="user_table">
                        <thead class="theader text-white" style="background-color:#417DBE">
                            <th class="text-center" style="min-width: 4rem;">Sl. No</th>
                            <th class="text-center" style="min-width: 8rem;">User Name</th>
                            <th class="text-center" style="min-width: 8rem;">Designation</th>
                            <th class="text-center" style="min-width: 8rem;">Office Type</th>
                            <th class="text-center" style="min-width: 8rem;">Office</th>
                            <th class="text-center" style="min-width: 8rem;">From</th>
                            <th class="text-center" style="min-width: 8rem;">To</th>
                            <th class="text-center" style="min-width: 8rem;">Reason</th>
                        </thead>

                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($userLists as $userList)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td>
                                        <a
                                            href="{{ URL::temporarySignedRoute('userMovement', now()->addMinutes(60), ['id' => $userList->user_id, 'user' => session('userName')]) }}">
                                            {{ $userList->user_name }}
                                        </a>
                                    </td>
                                    <td>{{ $desgName }}</td>
                                    <td>{{ $userList->office_type_desc }}</td>
                                    <td>{{ $userList->office_name }}</td>
                                    <td>{{ $userList->assign_from }}</td>
                                    <td>{{ $userList->assign_to ? $userList->assign_to : 'Present' }}</td>
                                    <td>{{ $userList->reason }}</td>
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
