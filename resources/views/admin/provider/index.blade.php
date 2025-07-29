@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> Provider
    </h4>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Daftar Provider</h5>
            @if (auth()->user()->can('create-ambulance-type'))
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.providers.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-sm-2"></i>
                        <span class="d-none d-sm-inline-block">Tambah Provider</span>
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
            <table class="table" id="providers-table">
                <thead>
                    <tr>
                        <th width="7%">#</th>
                        <th>Nama</th>
                        <th>PIC</th>
                        <th>Email</th>
                        <th>Telfon</th>
                        <th>Alamat</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page-js')
@include('layouts.script_datatables')
<script>
    $(function() {
        $('#providers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.providers.index") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'pic_name', name: 'pic_name' },
                { data: 'email', name: 'email' },
                { data: 'phone_number', name: 'phone_number' },
                { data: 'address', name: 'address' },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center'
                }
            ],
            responsive: true,
            lengthChange: false,
            pageLength: 10,
            language: {
                search: "Cari:",
                zeroRecords: "Tidak ada data yang ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush
