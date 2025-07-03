@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> Hak Akses
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Hak Akses</h5>
            @if (auth()->user()->can('create-permissions'))
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-sm-2"></i>
                    <span class="d-none d-sm-inline-block">Tambah Hak Akses</span>
                </a>
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
            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th width="7%">#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@include('components.delete-modal')

@push('page-js')
    @include('layouts.script_datatables')
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[1, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.permissions.index') }}',
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'name', name: 'name' },
                    { data: 'slug', name: 'slug' },
                    { 
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Handle delete button click on dynamically loaded content
            $('#datatable').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                let name = $(this).data('name');
                $('.delete-type').html('Hak Akses');
                $('.delete-hint').html(name);

                $('.btn-confirm-delete').off('click').on('click', function() {
                    $('.deleteModalForm').attr('action', url).submit();
                });
            });
        });
    </script>
@endpush

@endsection
