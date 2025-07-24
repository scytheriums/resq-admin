@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> Rate & Review
    </h4>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Rata-rata rate</h5>
                    <p class="display-6 mb-0">{{ $avgRating ? number_format($avgRating) : '-' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Pengemudi dengan rata-rata rating terendah</h5>
                    @if(!empty($worstDriverArr))
                        <p class="mb-1 fw-bold">{{ $worstDriverArr['name'] }}</p>
                        <p class="mb-0">{{ $worstDriverArr['avg_bad_rating'] }}</p>
                    @else
                        <p class="display-6 mb-0">{{ '-' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Daftar Rate & Review</h5>
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
                        <th>Order</th>
                        <th>Rater</th>
                        <th>Driver</th>
                        <th>Rate</th>
                        <th>Review</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<template id="template">
    Filter : 
  <label>
    <select name="driver_id" aria-controls="driver_id" class="form-select custom-select">
        <option value="" selected>Filter Driver</option>
        @foreach (\App\Models\Driver::all() as $dso)
          <option value="{{ $dso->id }}">{{ $dso->name }}</option>
        @endforeach
    </select>
  </label>
</template>
@include('components.delete-modal')
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    @include('layouts.script_datatables')
    <script>
        $(document).ready(function() {
            let table = $('#drivers-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'asc']],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.reviews.index') }}',
                    data: function(d) {
                        d.driver_id = $('[name=driver_id]').val();
                    }
                },
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'order.order_number'},
                    { data: 'user.name'},
                    { data: 'driver.name'},
                    { data: 'rating', className: 'text-center'},
                    { data: 'comment'},
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
                    $('.delete-type').html('Rate & Review');
                    $('.delete-hint').html(name);

                    $('.btn-confirm-delete').off('click').on('click', function(e) {
                        $('.deleteModalForm').attr('action', url);
                        $('.deleteModalForm').submit();
                    });
                });
            });

            $('.dataTables_length').html($('#template').html());
            $('[name=driver_id]').select2({
                placeholder: 'Filter Driver'
            }).on('select2:select', function(e) {
                table.draw();
            });
        });
    </script>
@endpush
