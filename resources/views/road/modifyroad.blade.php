@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('dashboard') }}" class="mr-2">Dashboard</a>/ Road Modification Request
        </div><br>
    </div>
    <!-- Main content -->
    <section class="content">

        <div class="container-fluid mt-3">
            <table class="table table-bordered table-striped user_list w-100" id="roadModify">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">Sl No.</th>
                    <th class="text-center">Request Id </th>
                    <th class="text-center">Asset Name</th>
                    <th class="text-center">Road System Id</th>
                    <th class="text-center">View Details</th>
                    <th class="text-center">Request By</th>
                    <th class="text-center">Request Date</th>
                    <th class="text-center">Status</th>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($reqPending as $r)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td class="text-center">{{ $r->request_id }}</td>
                            <td class="text-center">{{ $r->asset_name }}</td>
                            <td class="text-center">{{ $r->rd_system_id }}</td>
                            <td class="text-center">
                                <a href="" data-toggle="modal" data-target="#viewModal{{ $r->request_id }}"><i
                                        class="fas fa-eye mr-2 text-primary"></i></a>
                            </td>
                            <td class="text-center">{{ $r->name }}</td>
                            <td class="text-center">{{ $r->created_at }}</td>
                            @if ($r->modification_request_status == 'N')
                                <td class="text-center text-danger">New</td>
                            @elseif($r->modification_request_status == 'A')
                                <td class="text-center text-success">Approved</td>
                            @endif
                        </tr>
                        <?php $i++; ?>

                        <!-- view modal -->
                        <div class="modal fade" id="viewModal{{ $r->request_id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form id="finalApprove" class="finalApprove" action="{{ route('finalApproveByAdmin') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $r->request_id }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Road Modification
                                                Request</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 text-center coll"><b>Asset Name:
                                                        {{ $r->asset_name }}
                                                        <br>
                                                        @if ($r->is_sub_asset == 'Y')
                                                            Asset CD: {{ $r->sub_asset_cd }}
                                                            Road System CD: {{ $r->rd_system_id }}
                                                        @else
                                                            Road System CD:{{ $r->rd_system_id }}
                                                        @endif
                                                    </b></div>
                                            </div>

                                            <div class="row border-striped" style="background-color: #417DBE; color: white">

                                                <div class="col-md-3 text-center coll">Field Name</div>
                                                <div class="col-md-3 text-center coll">Reason of Update</div>
                                                <div class="col-md-3 text-center coll">Update From</div>
                                                <div class="col-md-3 text-center coll">Update To</div>
                                            </div>


                                            @if ($r->modification_request_status == 'N')
                                                @php
                                                    $req_dtlsJson = json_decode($r->modification_request_dtls);
                                                    $req_dtlsArr = $req_dtlsJson->req_dtls;
                                                @endphp
                                                @foreach ($req_dtlsArr as $data)
                                                    <div class="row border-striped">

                                                        <div class="col-md-3 text-center coll">
                                                            {{ $data->user_field_name }}</div>
                                                        <div class="col-md-3 text-center coll">
                                                            {{ $data->modification_reason }}
                                                        </div>
                                                        <div class="col-md-3 text-center coll">
                                                            {{ $data->old_value_descr }}
                                                        </div>
                                                        <div class="col-md-3 text-center coll">
                                                            {{ $data->new_value_descr }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1 mb-1">
                                                <label for="name" class="col-form-label">Remark</label>
                                            </div>
                                            <div class="col-md-11 mb-1">
                                                <textarea name="txtRemark" id="txtRemark" rows="3" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            @if ($r->modification_request_status == 'N')
                                                <button type="submit" class="btn btn-success btn-sm submitEditBtn"
                                                    style="border-radius:5px" id="btnApproveReject" value="Approve"><i
                                                        class="fas fa-solid fa-save"></i>
                                                    Approve</button>
                                                <button type="submit" class="btn btn-danger btn-sm submitrejectBtn"
                                                    style="border-radius:5px" id="btnApproveReject" value="Reject"><i
                                                        class="fas fa-solid fa-save"></i>
                                                    Reject</button>
                                            @endif
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->
                    @endforeach

                </tbody>
            </table>
        </div>
    </section>
@endsection
@push('styles')
    <style>
        .border-striped {
            border: 1px solid black;
        }

        .coll {
            padding-bottom: 10px;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("#roadModify").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadModify_wrapper .col-md-11:eq(1)');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.finalApprove').on("submit", function(e) {

                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                // return;

                var apprvReject = $(document.activeElement).val();
                formData = formData + "&approve_or_reject=" + apprvReject;

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response.message == 'success') {

                            if (response.action == 'Approved') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'success',
                                    text: "Modification Request Approved Successfully",
                                    showConfirmButton: true,
                                    timer: 5000
                                }).then(() => {
                                    location.reload();
                                });
                            } else
                                Swal.fire({
                                    icon: 'success',
                                    title: 'success',
                                    text: "Modification Request Rejected Successfully",
                                    showConfirmButton: true,
                                    timer: 5000
                                }).then(() => {
                                    location.reload();
                                });
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'error',
                            title: 'error',
                            text: "Something went wrong. Try Again !!",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });
        });
    </script>
@endpush
