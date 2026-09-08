@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('userMIS') }}">User MIS</a></li>
                        <li class="breadcrumb-item">User List </li>
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
                    <p class="text-danger"><i class="fa-solid fa-notdef text-danger"></i> No User record available right
                        now.</p>
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
                            <th class="text-center" style="min-width: 8rem;">Name</th>
                            <th class="text-center" style="min-width: 8rem;">Phone</th>
                            <th class="text-center" style="min-width: 8rem;">Address 1</th>
                            <th class="text-center" style="min-width: 8rem;">Address 2</th>
                            <th class="text-center" style="min-width: 8rem;">Designation</th>
                            <th class="text-center" style="min-width: 8rem;">Office Type</th>
                            <th class="text-center" style="min-width: 8rem;">Office</th>
                        </thead>

                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($userLists as $userList)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td>
                                        <a
                                            href="{{ URL::temporarySignedRoute('userMovement', now()->addMinutes(60), ['id' => $userList->id, 'user' => session('userName')]) }}">
                                            {{ $userList->name }}
                                        </a>
                                    </td>
                                    <td>{{ $userList->phoneno }}</td>
                                    <td>{{ $userList->address1 }}</td>
                                    <td>{{ $userList->address2 }}</td>
                                    <td>{{ $desgName }}</td>
                                    <td>{{ $userList->office_type_desc }}</td>
                                    <td>{{ $userList->office_name }}</td>
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
