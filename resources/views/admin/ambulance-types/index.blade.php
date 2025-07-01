@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> Ambulance Types
    </h4>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Ambulance Types List</h5>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.ambulance-types.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-sm-2"></i>
                    <span class="d-none d-sm-inline-block">Add New Type</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="ambulance-types-table">
                    <thead>
                        <tr>
                            <th width="7%">#</th>
                            <th>Name</th>
                            <th>Base Price</th>
                            <th>Orders</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-js')
    @include('layouts.script_datatables')
    <script>
        $(document).ready(function() {
            $('#ambulance-types-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.ambulance-types.index') }}',
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'name', name: 'name' },
                    { data: 'base_price', name: 'base_price' },
                    { data: 'description', name: 'description' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        function deleteAmbulanceType(id) {
            if (confirm('Are you sure you want to delete this ambulance type?')) {
                $.ajax({
                    url: '{{ route('admin.ambulance-types.destroy', '_id') }}/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert('Failed to delete ambulance type. Please try again.');
                    }
                });
            }
        }
    </script>
@endpush
