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
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.settings.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-sm-2"></i>
                    <span class="d-none d-sm-inline-block">Tambah Baru</span>
                </a>
            </div>
        </div>
        <div class="table-responsive card-datatable">
            <table class="table" id="settings-table">
                <thead>
                    <tr>
                        <th>Kunci</th>
                        <th>Nilai</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings as $setting)
                        <tr>
                            <td>{{ $setting->getAttributes()['key'] }}</td>
                            <td>{{ $setting->value }}</td>
                            <td>{{ $setting->description }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('admin.settings.edit', $setting->getAttributes()['key']) }}" class="text-body">
                                        <i class="ti ti-edit ti-sm me-2"></i>
                                    </a>
                                    <a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="{{ route('admin.settings.destroy', $setting->getAttributes()['key']) }}" data-name="{{ $setting->getAttributes()['key'] }}"> <i class="ti ti-trash ti-sm mx-2"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data pengaturan. <a href="{{ route('admin.settings.create') }}">Buat baru</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
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
            $('#settings-table').DataTable({
                responsive: true
            });

            $('.btn-delete').on('click', function(e) {
                let url = $(this).data('url');
                let name = $(this).data('name');
                $('.delete-type').html('Pengaturan');
                $('.delete-hint').html(name);

                $('.btn-confirm-delete').on('click', function(e) {
                    $('.deleteModalForm').attr('action', url)
                    $('.deleteModalForm').submit();
                });
            });
        });
    </script>
@endpush