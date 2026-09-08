<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Assets Images Page</title>
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
       <h4 class="text-uppercase">View  {{$assetName}} Assets Images</span></h4>
       <h5 class="text-uppercase">ASSET CD: <span style="font-weight: normal;">{{$assetCd}}</span></h5>
       
        <div class="row">
            @if($imageLists)
            @foreach ($imageLists as $image)
                <div class="col-md-3 my-1 justify-content-end align-item-center text-center">
                    <div class="shadow ">
                        {{-- <a href="{{ $image['img_url'] }}" target="_blank"> --}}
                            <img src="{{ $image['img_url'] }}" alt="Asset Image" style="width:100%; height: 18rem;">
                        {{-- </a> --}}
                    </div>
                </div>
            @endforeach
            @else
            <div class="col-md-12 justify-content-end align-item-center text-center">
                    <h5>No Image Avaliable</span></h5>
            </div>
            @endif
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script lang="javascript">
        function openImageInNewPage(imgUrl)
        {
            // alert(imgUrl);
            window.open(imgUrl, "_blank");
        }
    </script>
</body>

</html>