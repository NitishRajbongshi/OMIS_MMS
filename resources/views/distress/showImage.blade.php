<!DOCTYPE html>
<html lang="en">

<head>
    <title>Distress Image</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid" style="background-image:linear-gradient(to right, #141f76, #6773f5, #6773f5, #141f76)">
        <div class="row" style="padding:8px">
            <div class="col-md-1 col-sm-12 text-center me-4" style="">
                <img src="{{ asset('images/main_logo.png') }}" alt="logo" width="110rem"
                    style="padding-top:6%;padding-bottom:6%;border-radius: 100%;">
            </div>
            <div class="col-md-9 col-sm-12 text-white" style="padding: 1% 0% 0% 2%; font-weight:900">
                <span style="font-size:24px">ASSET MANAGEMENT AND INFORMATION SYSTEM</span><br>
                <span style="font-size:14px">NAGALAND PWD (R&B)</span><br>
                <span style="font-size:12px">Government of Nagaland</span>
            </div>
        </div>
    </div>
    <div class="container my-4">
        <div class="">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item">View Distress Images</li>
            </ol>
        </div>
        <h5 class="text-uppercase">ROAD NAME: <span style="font-weight: normal;">{{$distressDetails->rd_name}}</span></h5>
        <p class="border-bottom py-2">DEPARTMENTAL NOTE: {{$distressDetails->distress_remarks}}</p>
        <div class="row">
            @foreach ($imageLists as $image)
                <div class="col-md-3 my-1">
                    <div class="shadow">
                        <a href="{{ $image['img_url'] }}" target="_blank">
                            <img src="{{ $image['img_url'] }}" alt="Distress Image" style="width:100%; height: 18rem;">
                            {{-- <div class=" text-center py-2 text-decoration-none">
                                <p>Open in a new window</p>
                            </div> --}}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
