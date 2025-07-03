@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> Pengaturan
    </h4>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Pengaturan Aplikasi</h5>
            @if (auth()->user()->can('create-setting'))
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.settings.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-sm-2"></i>
                        <span class="d-none d-sm-inline-block">Tambah Baru</span>
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
            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th width="7%">#</th>
                        <th>Kunci</th>
                        <th>Nilai</th>
                        <th>Deskripsi</th>
                        <th width="10%">Aksi</th>
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
            var table = $('#datatable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[1, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.settings.index') }}',
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'key', name: 'key' },
                    { 
                        data: 'value', 
                        name: 'value',
                        render: function(data, type, row) {
                            return data.length > 50 ? data.substring(0, 50) + '...' : data;
                        }
                    },
                    { 
                        data: 'description', 
                        name: 'description',
                        render: function(data, type, row) {
                            return data ? (data.length > 50 ? data.substring(0, 50) + '...' : data) : '-';
                        }
                    },
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
                $('.delete-type').html('Pengaturan');
                $('.delete-hint').html(name);

                $('.btn-confirm-delete').off('click').on('click', function() {
                    $('.deleteModalForm').attr('action', url).submit();
                });
            });
        });
    </script>
@endpush