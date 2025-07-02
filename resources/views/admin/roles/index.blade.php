@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> Peran
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Peran</h5>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-sm-2"></i>
                <span class="d-none d-sm-inline-block">Tambah Peran</span>
            </a>
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
                        <th>Nama</th>
                        <th width="10%">Jumlah Pengguna</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->users_count }}</td>
                            <td width="15%">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-body">
                                        <i class="ti ti-edit ti-sm me-2"></i>
                                    </a>
                                    <a href="#" class="text-body delete-record btn-delete" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal" 
                                        data-url="{{ route('admin.roles.destroy', $role->id) }}" 
                                        data-name="{{ $role->name }}">
                                        <i class="ti ti-trash ti-sm mx-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('components.delete-modal')

@push('page-js')
    @include('layouts.script_datatables')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'asc']]
            });

            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                let name = $(this).data('name');
                $('.delete-type').html('Peran');
                $('.delete-hint').html(name);

                $('.btn-confirm-delete').off('click').on('click', function() {
                    $('.deleteModalForm').attr('action', url).submit();
                });
            });
        });
    </script>
@endpush

@endsection
