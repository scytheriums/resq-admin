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
        <span class="text-muted fw-light">Admin /</span> Pesanan
    </h4>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Daftar Pesanan</h5>
        </div>
        <div class="table-responsive card-datatable">
            <table class="table" id="orders-table">
                <thead>
                    <tr>
                        <th width="7%">#</th>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Review</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page-js')
    @include('layouts.script_datatables')
    <script>
        $(document).ready(function() {
            $('#orders-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.orders.index') }}',
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'order_number' },
                    { data: 'name' },
                    { data: 'driver', name: 'driver.name' },
                    { data: 'order_status', className: 'text-center' },
                    { data: 'payment_status', className: 'text-center' },
                    { data: 'review.rating', className: 'text-center', defaultContent: 'N/A' },
                    { 
                        data: 'action',
                        className: 'text-center',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
