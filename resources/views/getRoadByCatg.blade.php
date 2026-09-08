@extends('layouts.index')
@section('content')
    <main class="public-list-page">
        <section class="public-list-hero">
            <div class="public-list-brand">
                <img src="{{ asset('images/main_logo.png') }}" alt="Nagaland PWD logo">
                <div>
                    <span class="public-list-eyebrow">Citizen Asset Information</span>
                    <h1>Road Asset Registry</h1>
                    <p>Nagaland PWD {{ $catg_descr ? ' - ' . $catg_descr : '' }}</p>
                </div>
            </div>
            <div class="public-list-actions">
                <button type="button" class="oamis-theme-toggle" data-theme-toggle aria-pressed="false">
                    <i class="fas fa-moon"></i><span>Dark</span>
                </button>
                <a href="{{ route('getWelcomeDashBoard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </section>
        <section class="public-list-card">
            <div class="public-list-title">
                <h2>Road details by category</h2>
                <span>{{ $catg_descr }}</span>
            </div>
            <div class="public-list-table-wrap">
                <table class="table-responsive text-xs table table-bordered table-striped user_list" 
                    id="road_details_table">
                    <thead class="theader text-xs text-white" style="background-color:#417dbe">
                        <th class="text-center" style="min-width: 4rem;">Sl. No</th>
                        <th class="text-center" style="min-width: 6rem;">Road ID</th>
                        <th class="text-center" style="min-width: 10rem;">Road Name</th>
                        <th class="text-center" style="min-width: 10rem;">
                            Road Type
                            <select name="cust_serach_rd_type" id="cust_serach_rd_type">
                                <option value="A">All</option>
                                @foreach ($rdTypeMaster as $item)
                                    <option value={{ $item->rd_type_descr }}>
                                        {{ $item->rd_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </th>
                        <th class="text-center" style="min-width: 6rem;">Length(Km)</th>
                        <th class="text-center" style="min-width: 10rem;">
                            Division
                            <select name="cust_serach_div" id="cust_serach_div">
                                <option value="A">All</option>
                                @foreach ($divMaster as $item)
                                    <option value={{ $item->division_name }}>
                                        {{ $item->division_name }}
                                    </option>
                                @endforeach
                            </select>
                        </th>
                        <th class="text-center" style="min-width: 8rem;">Road Owner</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($roadDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->rd_system_id }}
                                </td>
                                <td>
                                    {{ $item->rd_name }}
                                </td>
                                <td>
                                    {{ $item->rd_type_descr }}
                                </td>
                                <td>
                                    {{ $item->road_length }}
                                </td>
                                <td>
                                    {{ $item->division_name }}
                                </td>
                                <td>
                                    {{ $item->owner_name }}
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-list.css') }}">
@endpush
@push('scripts')
    <script>
        $(function() {
        
            $('#road_details_table').DataTable();
        
        });
        
        $(function() {
            let filedName = null;
            const rd_tp = document.querySelector('#cust_serach_rd_type');
            const rd_divison = document.querySelector('#cust_serach_div');

            DataTable.ext.search.push(function(settings, data, dataIndex) {

                let selectedRdcatg = $("#cust_serach_rd_type :selected").text();
                let selectedRdDiv = $("#cust_serach_div :selected").text();
                if (filedName == "road_type") {
                    if (
                        (isNaN(selectedRdcatg) && selectedRdcatg.trim() == data[3].trim()) ||
                        (isNaN(selectedRdcatg) && selectedRdcatg.trim() == "All")) {
                        return true;
                    }
                }

                if (filedName == "road_divison") {
                    if ((isNaN(selectedRdDiv) && selectedRdDiv.trim() == data[5].trim()) ||
                        (isNaN(selectedRdDiv) && selectedRdDiv.trim() == "All")) {
                        return true;
                    }
                }
                if (filedName == null)
                    return true;
                return false;
            });

            const myDataTable = new DataTable('#road_details_table');

            rd_tp.addEventListener('change', function() {
                filedName = "road_type";
                myDataTable.draw();
            });

            rd_divison.addEventListener('change', function() {
                filedName = "road_divison";
                myDataTable.draw();
            });
        });
    </script>
    
@endpush
