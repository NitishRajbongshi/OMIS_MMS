<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add National Highway</title>
    <style>
        .mainBody {
            background-color: #FEFBFA;
            margin: 3px 10px 5px 10px;
            color: #11113B;
        }


        label {
            font-size: 12px;
        }

        .fl {
            border: 2px solid blue !important;
        }

        legend {
            line-height: 30px;
            font-weight: 600;
            text-transform: uppercase;
            color: black;
            padding: 2px 2px 2px 2px;
            border: 2px solid #5677E7;
        }

        .star {
            color: red;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts/header')
        @include('sweet::alert')
        <div class="content-header mb-4">
            <div class="container-fluid">
                <ol class="breadcrumb float-sm-left text-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('nationalHighway') }}">Manage NH</a>
                    </li>
                    <li class="breadcrumb-item">Add New Highway</li>
                </ol>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid mainBody py-3">
                @if (session('failed'))
                    <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fa fa-info" aria-hidden="true"></i>
                        <strong>Failed!</strong> {{ session('failed') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('invalid'))
                    <div class="alert alert-success">
                        {{ session('invalid') }}
                    </div>
                @endif

                <form action="{{ route('addNationalHighway') }}" method="post" autocomplete="off">
                    @csrf
                    <fieldset class="border p-3 fl">
                        <legend class="w-auto px-2" style="font-size:16px">Highway Information</legend>
                        <div class="row form-1-box">
                            <div class="col-md-3">
                                <input type="hidden" id="userOfficeType" value="{{ $user->office_type_cd }}">
                            </div>
                        </div>
                        <div class="row form-1-box">
                            <div class="col-md-3">
                                <label for="highway_name">Highway Name<span class="star">*</span></label>
                                <input type="text" id="highway_name" class="form-control" name="highway_name">

                                @error('highway_name')
                                    <div class="text-danger mt-2 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="highway_category">Highway Category <span class="star">*</span></label>
                                <select id="highway_category" class="custom-select form-control"
                                    name="highway_category">
                                    <option value="" disable selected hidden>Please Select</option>
                                    @foreach ($roadCategories as $roadCategory)
                                        <option value="{{ $roadCategory->rd_catg_cd }}">
                                            {{ $roadCategory->rd_catg_descr }}</option>
                                    @endforeach
                                </select>

                                @error('highway_category')
                                    <div class="text-danger mt-2 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="highway_type">Highway Type <span class="star">*</span></label>
                                <select id="highway_type" class="custom-select form-control" name="highway_type">
                                    <option value="" disable selected hidden>Please Select</option>
                                    @foreach ($roadTypes as $roadType)
                                        <option value="{{ $roadType->rd_type_cd }}">
                                            {{ $roadType->rd_type_descr }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('highway_type')
                                    <div class="text-danger mt-2 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="highway_number">Highway Number <span class="star">*</span></label>
                                <input type="text" id="highway_number" class="form-control" name="highway_number">

                                @error('highway_number')
                                    <div class="text-danger mt-2 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-1-box">
                            <div class="col-md-3">
                                <label for="highway_length">Highway Length (Metr.)<span class="star">*</span></label>
                                <input type="number" step="0.001" id="highway_length" class="form-control"
                                    name="highway_length" placeholder="0.000">

                                @error('highway_length')
                                    <div class="text-danger mt-2 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="highway_owner">Highway Owner <span class="star">*</span></label>
                                <select id="highway_owner" class="custom-select form-control" name="highway_owner">
                                    <option value="" disable selected hidden>Please Select</option>
                                    @foreach ($roadOwners as $roadOwner)
                                        <option value="{{ $roadOwner->owner_cd }}">{{ $roadOwner->owner_name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('highway_owner')
                                    <div class="text-danger mt-2 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2"><i class="fa fa-save"></i>
                        Submit</button>
                    <button class="btn btn-danger btn-sm rounded-0 mt-2"><i class="fa fa-backward"></i><a
                            class="text-white" href="{{ route('nationalHighway') }} ">
                            Cancel</a></button>
                </form>
            </div>
        </section>
    </div>
    @include('layouts/footer')
</body>

</html>
