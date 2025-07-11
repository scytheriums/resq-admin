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
        <span class="text-muted fw-light">Admin /</span> Pengemudi
    </h4>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Daftar Pengemudi</h5>
            @if (auth()->user()->can('create-driver'))
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-sm-2"></i>
                        <span class="d-none d-sm-inline-block">Tambah Pengemudi</span>
                    </a>
                </div>
            @endif
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="table-responsive card-datatable">
            <table class="table" id="drivers-table">
                <thead>
                    <tr>
                        <th width="7%">#</th>
                        <th>Nama</th>
                        <th>No. Telf</th>
                        <th>Nomor Plat</th>
                        <th>Ambulan</th>
                        <th>Avg. Rating</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@include('components.delete-modal')
@endsection

@push('page-js')
    @include('layouts.script_datatables')
    <script>
        $(document).ready(function() {
            $('#drivers-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.drivers.index') }}',
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'name'},
                    { data: 'phone_number'},
                    { data: 'license_plate'},
                    { data: 'ambulance_type.name'},
                    { data: 'average_rating', className: 'text-center'},
                    { 
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            }).on('draw.dt', function() {
                $('.btn-delete').on('click', function(e) {
                    e.preventDefault();
                    let url = $(this).data('url');
                    let name = $(this).data('name');
                    $('.delete-type').html('Driver');
                    $('.delete-hint').html(name);

                    $('.btn-confirm-delete').off('click').on('click', function(e) {
                        $('.deleteModalForm').attr('action', url);
                        $('.deleteModalForm').submit();
                    });
                });
            });
        });
    </script>
@endpush
