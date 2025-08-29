@extends('layouts.master')

@section('vendor-css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / Tipe Ambulance /</span> Edit Tipe Ambulance
    </h4>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Edit Tipe Ambulance</h5>
        </div>
        <div class="card-body">
            <form id="form" action="{{ route('admin.ambulance-types.update', $ambulanceType) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="vehicle_id" class="form-label">Kendaraan Ambulance</label>
                        <select class="form-select select2 @error('vehicle_id') is-invalid @enderror" 
                            id="vehicle_id" name="vehicle_id" required>
                            <option value="">Pilih Kendaraan Ambulance</option>
                            @foreach($ambulanceVehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $ambulanceType->ambulance_vehicles_id) == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->vehicle_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <h5>Tarif Berdasarkan Jarak</h5>
                        <div id="tarifContainer">
                            @php
                                $tarifs = old('tarifs') ?? $ambulanceType->tarifs->toArray();
                                if (empty($tarifs)) {
                                    $tarifs = [['min_distance' => '', 'max_distance' => '', 'tarif' => '']];
                                }
                            @endphp
                            @foreach($tarifs as $index => $tarif)
                                <div class="tarif-row row g-3 mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Jarak Minimal (KM)</label>
                                        <input type="number" name="tarifs[{{ $index }}][min_distance]" 
                                            class="form-control @error('tarifs.' . $index . '.min_distance') is-invalid @enderror" 
                                            value="{{ old('tarifs.' . $index . '.min_distance', $tarif['min_distance'] ?? '') }}" 
                                            min="0" required>
                                        @error('tarifs.' . $index . '.min_distance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Jarak Maksimal (KM)</label>
                                        <input type="number" name="tarifs[{{ $index }}][max_distance]" 
                                            class="form-control @error('tarifs.' . $index . '.max_distance') is-invalid @enderror" 
                                            value="{{ old('tarifs.' . $index . '.max_distance', $tarif['max_distance'] ?? '') }}" 
                                            min="0" required>
                                        @error('tarifs.' . $index . '.max_distance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tarif (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="tarifs[{{ $index }}][tarif]" 
                                                class="form-control @error('tarifs.' . $index . '.tarif') is-invalid @enderror" 
                                                value="{{ old('tarifs.' . $index . '.tarif', $tarif['tarif'] ?? '') }}" 
                                                min="0" required>
                                            @error('tarifs.' . $index . '.tarif')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-sm btn-danger btn-remove-tarif" @if($loop->first) style="display: none;" @endif>
                                            <i class="ti ti-x ti-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="addTarif">
                            <i class="bx bx-plus"></i> Tambah Tarif
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="free_tarif_for_purpose" class="form-label">Gratiskan Tarif untuk Tujuan Tertentu</label>
                        <select class="form-control select2 @error('free_tarif_for_purpose') is-invalid @enderror" id="free_tarif_for_purpose" name="free_tarif_for_purpose[]" multiple="multiple">
                            @foreach(\App\Models\Purpose::all() as $purpose)
                                <option value="{{ $purpose->id }}" {{ in_array($purpose->id, old('free_tarif_for_purpose', $ambulanceType->free_tarif_for_purpose ?? [])) ? 'selected' : '' }}>
                                    {{ $purpose->name }}
                                </option>
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
            <button type="submit" form="form" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Perubahan</button>
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
            // Initialize vehicle select
            $('#vehicle_id').select2({
                placeholder: 'Pilih Kendaraan Ambulance'
            });

            // Initialize purpose multi-select
            $('#free_tarif_for_purpose').select2({
                placeholder: 'Pilih tujuan layanan',
                allowClear: true,
                multiple: true
            });

            // Add new tarif row
            let tarifIndex = {{ count(old('tarifs', $ambulanceType->tarifs)) }};
            $('#addTarif').click(function() {
                const newRow = `
                    <div class="tarif-row row g-3 mb-3">
                        <div class="col-md-3">
                            <input type="number" name="tarifs[${tarifIndex}][min_distance]" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="tarifs[${tarifIndex}][max_distance]" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="tarifs[${tarifIndex}][tarif]" class="form-control" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-danger btn-remove-tarif">
                                <i class="ti ti-x ti-sm"></i>
                            </button>
                        </div>
                    </div>`;
                
                $('#tarifContainer').append(newRow);
                tarifIndex++;
                updateRemoveButtons();
            });

            // Remove tarif row
            $(document).on('click', '.btn-remove-tarif', function() {
                $(this).closest('.tarif-row').remove();
                updateRemoveButtons();
            });

            // Show/hide remove button based on number of rows
            function updateRemoveButtons() {
                const $rows = $('.tarif-row');
                $('.btn-remove-tarif').toggle($rows.length > 1);
            }

            // Initialize with one row
            updateRemoveButtons();
        });
    </script>
@endpush