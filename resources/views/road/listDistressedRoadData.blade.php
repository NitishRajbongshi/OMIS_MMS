@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left text-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Road Distress Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content mainBody" id="roadDistressedDetails" name="roadDistressedDetails">
        <div class="container-fluid border py-2">
            <table class="table-responsive table table-bordered text-xs table-striped user_list" id="roadDetail">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">Serial No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">District Name</th>
                    <th class="text-center">Block Name</th>
                    <th class="text-center">Division Name</th>
                    <th class="text-center">Distress Type</th>
                    <th class="text-center">Distress From</th>
                    <th class="text-center">Distress To</th>
                    <th class="text-center">Distress Length(KM)</th>
                    <th class="text-center" style="min-width: 6rem;">Date of Occurance</th>
                    <th class="text-center" style="min-width: 4rem;">Days To Restore</th>
                    <th class="text-center">Restore Status</th>
                    <th class="text-center">Remark</th>
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($distrs_dtls as $key)
                        @php
                            $date_occur = \Carbon\Carbon::parse($key->date_of_occurance);
                            $todays_date = \Carbon\Carbon::now();
                            $date_diff = $date_occur->diffInDays($todays_date);
                        @endphp
                        @if ($date_diff > $key->days_to_restore)
                            <tr class="table-danger">
                            @else
                            <tr class="table-primary">
                        @endif
                        <td class="text-center">{{ $i }}</td>
                        <td style="position: relative">
                            {{ $key->rd_name }}
                        </td>

                        <td style="position: relative">
                            {{ $key->district_name }}
                        </td>

                        <td style="position: relative">
                            {{ $key->block_name }}
                        </td>
                        <td style="position: relative">
                            {{ $key->division_name }}
                        </td>
                        <td style="position: relative">
                            {{ $key->distress_type_descr }}
                        </td>
                        <td style="position: relative">
                            {{ $key->start_landmark }}
                        </td>
                        <td style="position: relative">
                            {{ $key->end_landmark }}
                        </td>
                        <td style="position: relative">
                            {{ $key->distress_length_in_km }}
                        </td>
                        <td style="position: relative">
                            {{ $key->date_of_occurance }}
                        </td>
                        <td style="position: relative">
                            {{ $key->days_to_restore }}
                        </td>
                        <td style="position: relative">
                            Not Restored
                        </td>
                        <td style="position: relative">
                            {{ $key->distress_remarks }}
                        </td>
                        <td style="position: relative" valign="center">
                            <a data-toggle="modal" data-target="#editDistressModal{{ $key->rd_distress_cd }}"
                                data-backdrop="static" class="btn btn-primary">
                                update</a>
                        </td>
                        </tr>
                        <?php $i++; ?>
                        <!-- edit modal Road Category-->
                        <div class="modal fade" id="editDistressModal{{ $key->rd_distress_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <form class="update_road_distress_form"
                                        id="update_road_distress_{{ $key->rd_distress_cd }}" method="POST"
                                        action="{{ route('list.distress') }}">
                                        @csrf
                                        <input type="hidden" name="distress_cd" value="{{ $key->rd_distress_cd }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Road
                                                Distress</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-10 mb-1">
                                                    <label for="name" class="col-form-label">Remarks
                                                        <span class="text-danger text-bold">*</span>
                                                        <textarea type="text" name="txt_distress_remark" id="txt_distress_remark" rows="3" class="form-control"
                                                            required></textarea>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 mb-1">
                                                    <label for="name" class="col-form-label">Distress
                                                        CD</label>
                                                    <input type="text" name="txt_distress_cd" id="txt_distress_cd"
                                                        rows="3" class="form-control"
                                                        value="{{ $key->rd_distress_cd }}" readonly>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <label for="name" class="col-form-label">Distress
                                                        Occured On </label>
                                                    <input type="text" name="txt_distress_date" id="txt_distress_date"
                                                        rows="3" class="form-control"
                                                        value="{{ $key->date_of_occurance }}" readonly>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5 mb-1">
                                                    <label for="name" class="col-form-label">Start
                                                        Landmark</label>
                                                    <input type="text" name="txt_start_landmark"
                                                        id="txt_start_landmark" rows="3" class="form-control"
                                                        value="{{ $key->start_landmark }}" required>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <label for="name" class="col-form-label">End
                                                        Landmark </label>
                                                    <input type="text" name="txt_end_landmark" id="txt_end_landmark"
                                                        rows="3" class="form-control"
                                                        value="{{ $key->end_landmark }}" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 mb-1">
                                                    <label for="name" class="col-form-label">Old Restore
                                                        Status
                                                    </label>
                                                    <input type="text" name="txt_old_restore_status"
                                                        id="txt_old_restore_status" rows="3" class="form-control"
                                                        value="Not Restored" readonly>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <label for="name" class="col-form-label">New Restore
                                                        Status</label>
                                                    <select id="sel_new_restore_status" class="custom-select form-control"
                                                        name="sel_new_restore_status">
                                                        <option value="" required>Please
                                                            Select</option>
                                                        <option value="N">Not Restored</option>
                                                        <option value="Y">Restored</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 mb-1">
                                                    <label for="name" class="col-form-label">No of Days to
                                                        Restore
                                                    </label>
                                                    <input type="text" name="txt_no_days_to_restore"
                                                        id="txt_no_days_to_restore" rows="3" class="form-control"
                                                        value="{{ $key->days_to_restore }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px">
                                                <i class="fa-solid fa-floppy-disk"></i> Send Request
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal">
                                                <i class="fa-solid fa-close"></i> Close</button>
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
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("#roadDetail").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetail_wrapper .col-md-11:eq(1)');
        });
        $(function() {
            $("#roadDetailForCulvert").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForCulvert_wrapper .col-md-4:eq(1)');
        });

        $(function() {
            $("#roadDetailForBridges").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForBridges_wrapper .col-md-4:eq(1)');
        });

        $(function() {
            $("#roadDetailForSurfaceTypes").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForSurfaceTypes_wrapper .col-md-4:eq(1)');
        });

        $(function() {
            $("#roadDetailForPavement").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForPavement_wrapper .col-md-4:eq(1)');
        });

        $(function() {
            $("#roadDetailForPCI").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForPCI_wrapper .col-md-4:eq(1)');
        });
        $('.modalClose').click(function() {
            //location.reload();
            $('.modal-body :input:not([readonly]), .modal-body textarea:not([readonly])').val('');
        });

        $('#modalDate').on('change', function() {
            var selectedDate = $(this).val();
            var dateObj = new Date(selectedDate);
            var year = dateObj.getFullYear();
            var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
            var day = ('0' + dateObj.getDate()).slice(-2);
            var formattedDate = year + '-' + month + '-' + day;
            $(this).val(formattedDate);
        });
    </script>
    <script>
        $(document).ready(function() {

            $('form.update_road_distress_form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var distres_cd = $('#txt_distress_cd').val();
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
                            $('#editDistressModal' + distres_cd).modal().hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Road Distress Data Updated Successfully!!!!\nDistress CD: " +
                                    response.distress_cd,
                                showConfirmButton: true,
                                timer: 5000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'error',
                                text: "Request Could Not Sent Successfully!!!!\nPlease Try Again ...",
                                showConfirmButton: true,
                                timer: 5000
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        $('#editDistressModal' + distres_cd).modal().hide();
                        Swal.fire({
                            icon: 'error',
                            title: 'error',
                            text: "Road Distress Data Could not be Updated Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                            // window.location.replace(location);
                        });
                    }
                });
            });
        });
    </script>
@endpush
