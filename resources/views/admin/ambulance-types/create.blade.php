@extends('layouts.master')

@section('vendor-css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / Tipe Ambulance /</span> Tambah Tipe Ambulance
    </h4>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Tambah Tipe Ambulance</h5>
        </div>
        <div class="card-body">
            <form id="form" action="{{ route('admin.ambulance-types.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Nama Tipe Ambulance</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama tipe ambulance" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tarif_dalam_kota" class="form-label">Tarif Dalam Kota</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('tarif_dalam_kota') is-invalid @enderror" 
                                id="tarif_dalam_kota" name="tarif_dalam_kota" 
                                value="{{ old('tarif_dalam_kota') }}" placeholder="Masukkan tarif dalam kota" required min="0">
                        </div>
                        @error('tarif_dalam_kota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tarif_km_luar_kota" class="form-label">Tarif /KM Luar Kota</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('tarif_km_luar_kota') is-invalid @enderror" 
                                id="tarif_km_luar_kota" name="tarif_km_luar_kota" 
                                value="{{ old('tarif_km_luar_kota') }}" placeholder="Masukkan tarif per KM luar kota" required min="0">
                        </div>
                        @error('tarif_km_luar_kota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tarif_km_luar_provinsi" class="form-label">Tarif /KM Luar Provinsi</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('tarif_km_luar_provinsi') is-invalid @enderror" 
                                id="tarif_km_luar_provinsi" name="tarif_km_luar_provinsi" 
                                value="{{ old('tarif_km_luar_provinsi') }}" placeholder="Masukkan tarif per KM luar provinsi" required min="0">
                        </div>
                        @error('tarif_km_luar_provinsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="free_tarif_for_purpose" class="form-label">Gratiskan Tarif untuk Tujuan Tertentu</label>
                        <select class="form-control select2 @error('free_tarif_for_purpose') is-invalid @enderror" id="free_tarif_for_purpose" name="free_tarif_for_purpose[]" multiple="multiple">
                            @foreach(\App\Models\Purpose::all() as $purpose)
                                <option value="{{ $purpose->id }}" {{ in_array($purpose->id, old('free_tarif_for_purpose', [])) ? 'selected' : '' }}>{{ $purpose->name }}</option>
                            @endforeach
                        </select>
                        @error('free_tarif_for_purpose')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-end gap-1 p-3 border-top">
            <a href="{{ route('admin.ambulance-types.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Batal</a>
            <button type="submit" form="form" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Tipe Ambulance</button>
        </div>
    </div>
</div>
@endsection

@section('vendor-js')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Pilih tujuan layanan',
                allowClear: true,
                multiple: true
            });
        });
    </script>
@endpush