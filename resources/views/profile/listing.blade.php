@extends('layouts.master')

@section('title', __('Listing Counts Report ( DB )'))

@section('content')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between d-flex">
                    <h2 class="card-title">Listing Counts Report ( DB )</h2>

                    <div class="d-flex" style="grid-gap: 10px;">
                        <div>
                            <a href="#" class="btn btn-light position-relative me-2 mb-2"> Pending
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $pending }}
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </a>
                            <a href="#" class="btn btn-warning position-relative me-2 mb-2"> Approved
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{$approved}}
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </a>
                            <a href="#" class="btn btn-danger position-relative mb-2"> Rejected
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{$rejected}}
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </a>
                        </div>
                        @if(auth()->user()->hasRole('Super Admin'))
                        <form action="" method="get" id='form' class="d-flex align-items-center">
                            <b class="w-100">User Filter</b>
                            <select class="form-control w-100" name="user" id='user'>
                                <option value="all">All Users</option>
                                @foreach($users as $user)
                                <option value="{{$user->id}}" {{ $user->id == request()->user ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </form>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check-all" /></th>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Product Title') }}</th>
                                    <th>{{ __('Created by') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Approved by') }}</th>
                                    <th>{{ __('Approved at') }}</th>
                                    <th>{{ __('Current Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userListings as $key => $userListing)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids" class="checkbox-update" value="{{$userListing->id}}" />
                                    </td>
                                    <td>{{ ++$key }}</td>
                                    <td><img onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $userListing->image }}" alt="Product Image" width="50" /></td>
                                    <td>{{ $userListing->title }}</td>
                                    <td>{{ $userListing->create_user->name }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($userListing->created_at)) }}</td>
                                    <td>
                                        @if($userListing->approved_by)
                                        {{ $userListing?->approve?->name }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        @if($userListing->approved_at)
                                        {{ date("d-m-Y h:i A", strtotime($userListing->approved_at)) }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        @if($userListing->status == "0")
                                        <button class="btn btn-warning btn-sm">Pending</button>
                                        @elseif($userListing->status == 1)
                                        <button class="btn btn-success btn-sm">Approved</button>
                                        @elseif($userListing->status == 2)
                                        <button class="btn btn-danger btn-sm">Rejected</button>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('profile.listing.delete.single', $userListing->id) }}" class="btn btn-primary btn-sm">Delete</a></td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

<script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
<script src="/assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
<script src="/assets/js/table-data.js"></script>

<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- TIMEPICKER JS -->
<script src="/assets/plugins/time-picker/jquery.timepicker.js"></script>
<script src="/assets/plugins/time-picker/toggles.min.js"></script>

<!-- DATEPICKER JS -->
<script src="/assets/plugins/date-picker/date-picker.js"></script>
<script src="/assets/plugins/date-picker/jquery-ui.js"></script>

<!-- COLOR PICKER JS -->
<script src="/assets/plugins/pickr-master/pickr.es5.min.js"></script>

<!-- FORMELEMENTS JS -->
<script src="/assets/js/form-elements.js"></script>

<script>
    $(document).ready(function() {
        //______Basic Data Table
        $('#basic-datatable').DataTable({
            "paging": false,
        });

        $("#category").on("change", function() {
            $("#form").submit();
        });

        $("#user").change(function() {
            $("#form").submit();
        });

        $("#basic-datatable_wrapper .col-sm-12:first").html('<form id="update-status" action="" method="GET"><div class="d-flex"><select class="form-control w-50" name="status"><option>Select</option><option value=1>Delete</option></select><button class="btn btn-primary update-status" style="margin-left:10px;">Update</button></div></form>');

        var ids = [];
        $(".checkbox-update").click(function() {
            if ($(this).prop('checked')) {
                ids.push($(this).val());
            } else {
                var index = ids.indexOf($(this).val());
                if (index !== -1) {
                    ids.splice(index, 1);
                }
            }
        });

        $(".table-responsive").on('click', '.update-status', function(e) {
            e.preventDefault();
            var formData = $('#update-status').serializeArray();
            formData.push(ids);

            if (ids.length <= 0) {
                alert('Please select any item')
                return true;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('profile.listing.delete') }}",
                data: {
                    formData: formData
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    if (result) {
                        window.location.href = location.href;
                    }
                },
            });
        });

        $('.check-all').click(function() {
            $(".checkbox-update").each(function() {
                if ($('.check-all').prop('checked') == true) {
                    $(this).prop('checked', true);
                    ids.push($(this).val());
                } else {
                    $(this).prop('checked', false);
                    var index = ids.indexOf($(this).val());
                    if (index !== -1) {
                        ids.splice(index, 1);
                    }
                }
            });
        });
    })
</script>

@endpush