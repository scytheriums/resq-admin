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
            <h4 class="card-title">Additional Services</h4>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.additional-services.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-sm-2"></i>
                    <span class="d-none d-sm-inline-block">Add New Service</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="additional-services-table">
                    <thead>
                        <tr>
                            <th width="7%">#</th>
                            <th>Name</th>
                            <th>Price</th>
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
            $('#additional-services-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.additional-services.index') }}',
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'name', name: 'name' },
                    { 
                        data: 'price',
                        render: function(data, type, row) {
                            return number_format(row.price, 2);
                        }
                    },
                    { data: 'orders_count', name: 'orders_count' },
                    { 
                        data: 'action',
                        render: function(data, type, row) {
                            return '<div class="btn-group">' +
                                '<a href="{{ route('admin.additional-services.edit', '_id') }}/' + row.id + '" class="btn btn-sm btn-warning">' +
                                    '<i class="bx bx-edit"></i>' +
                                '</a>' +
                                '<button type="button" class="btn btn-sm btn-danger delete-record" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="{{ route('admin.additional-services.destroy', '_id') }}/' + row.id + '" data-name="' + row.name + '">' +
                                    '<i class="bx bx-trash"></i>' +
                                '</button>' +
                            '</div>';
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
