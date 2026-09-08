@extends('layouts.app')
@section('content')
    <div class="row m-1 text-sm">
        <div class="col-sm-6 col-md-4">
            <ol class="breadcrumb float-sm-left"
                style="border-radius: 25px 0px 25px 0px; background-color:#E1F4F9; font-style: italic;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">View Profile</li>
            </ol>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid" id="viewBody">
            <div class="row">
                @foreach ($userDetails as $key)
                    <div class="col-md-6 col-sm-12 mt-2">
                        <div class='card' style="height:95%">
                            <div class="card-header text-white" id="cardHead">
                                <h3 class="card-title" style="font-weight: bold; text-transform:uppercase">personal
                                    information</h3>
                            </div>
                            <div class='card-body'>
                                <div class="row">
                                    <div class="col-md-3"><strong>Name</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    <div class="col-md-8">{{ $key->name }}</div>
                                    <div class="col-md-3"><strong>Contact No.</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    <div class="col-md-8">{{ $key->phoneno }}</div>
                                    <div class="col-md-3"><strong>Address</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    @if ($key->address1 != 'NULL')
                                        <div class="col-md-8">Village/City - {{ $key->address1 }} </div>
                                    @endif

                                    @if ($key->address2 != 'NULL')
                                        <div class="col-md-4"><strong></strong></div>
                                        <div class="col-md-8">Area - {{ $key->address2 }} </div>
                                    @endif

                                    @if ($key->district != 'NULL')
                                        <div class="col-md-4"><strong></strong></div>
                                        <div class="col-md-8">District - {{ $key->dist_name }} </div>
                                    @endif

                                    @if ($key->pin != 'NULL')
                                        <div class="col-md-4"><strong></strong></div>
                                        <div class="col-md-8">PIN - {{ $key->pin }} </div>
                                    @endif

                                    @if ($key->state != 'NULL')
                                        <div class="col-md-4"><strong></strong></div>
                                        <div class="col-md-8">State - {{ $key->state }} </div>
                                    @endif

                                    @if ($key->country != 'NULL')
                                        <div class="col-md-4"><strong></strong></div>
                                        <div class="col-md-8">Country - INDIA </div>
                                    @endif

                                    <div class="col-md-3"><strong>Gender</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    @if ($key->gender != 'NULL')
                                        <div class="col-md-8">{{ $key->gender }} </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12 mt-2">
                        <div class='card' style="height:95%; margin-bottom: 20px">
                            <div class="card-header text-white" id="cardHead">
                                <h3 class="card-title" style="font-weight: bold; text-transform:uppercase">official
                                    information</h3>
                            </div>
                            <div class='card-body'>
                                <div class="row">
                                    <div class="col-md-3"><strong>Email</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    <div class="col-md-8">{{ $key->email }}</div>

                                    <div class="col-md-3"><strong>Department</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    <div class="col-md-8">
                                        @if ($key->department != 'NULL')
                                            {{ $key->department_name }}
                                        @endif
                                    </div>

                                    <div class="col-md-3"><strong>Office</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    <div class="col-md-8">
                                        @if ($key->office != 'NULL')
                                            {{ $key->office_name }}
                                        @endif
                                    </div>

                                    <div class="col-md-3"><strong>Designation</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    <div class="col-md-8">
                                        @if ($key->designation != 'NULL')
                                            {{ $key->desg_name }}
                                        @endif
                                    </div>

                                    <div class="col-md-3"><strong>Post</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    <div class="col-md-8">
                                        @if ($key->post != 'NULL')
                                            {{ $key->post_name }}
                                        @endif
                                    </div>

                                    <div class="col-md-3"><strong>Office Type</strong></div>
                                    <div class="col-md-1"><strong>:</strong></div>
                                    <div class="col-md-8">
                                        @if ($key->office_type_cd != 'NULL')
                                            {{ $key->office_type_desc }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
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
