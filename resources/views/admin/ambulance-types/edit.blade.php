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
                    <!-- Kiri: Tarif Minimum -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tarif Minimum</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="tarif_minimum" name="tarif_minimum" class="form-control" min="0" value="{{ $ambulanceType->tarif_minimum }}" required>
                        </div>
                    </div>
                    <!-- Kanan: Tarif per KM -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tarif / KM</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="tarif_per_km" name="tarif_per_km" class="form-control bg-light" min="0" value="{{ $ambulanceType->tarif_per_km }}" readonly>
                        </div>
                        <small class="text-muted">Tarif per KM tidak dapat diubah pada mode edit</small>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Tarif Berdasarkan Jarak</h5>
                            <span class="badge bg-info" id="tierCounter">0 dari 3 Tier</span>
                        </div>
                        <div class="alert alert-info" role="alert">
                            <small><i class="bx bx-info-circle me-1"></i> Data tier tarif sudah tersimpan di database. Anda dapat menambah tier baru atau mengedit tier yang ada.</small>
                        </div>
                        <div id="tarifContainer">
                            @php
                                $tarifs = old('tarifs') ?? $ambulanceType->tarifs->toArray();
                                if (empty($tarifs)) {
                                    $tarifs = [['min_distance' => '', 'max_distance' => '', 'tarif' => '']];
                                }
                            @endphp
                            @foreach($tarifs as $index => $tarif)
                                <div class="tarif-row row g-3 mb-3" data-tier="{{ $index + 1 }}">
                                    <div class="col-md-3">
                                        <label class="form-label">Jarak Minimal (KM) <small class="text-muted">Tier {{ $index + 1 }}</small></label>
                                        <input type="number" name="tarifs[{{ $index }}][min_distance]" 
                                            class="form-control @error('tarifs.' . $index . '.min_distance') is-invalid @enderror" 
                                            value="{{ old('tarifs.' . $index . '.min_distance', $tarif['min_distance'] ?? '') }}" 
                                            min="0" required>
                                        @if($index > 0)
                                            <small class="text-muted">Minimal: {{ $ambulanceType->tarifs[$index-1]['max_distance'] ?? 0 + 1 }} KM</small>
                                        @endif
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
                                    <div class="col-md-1">
                                        <label class="form-label">&nbsp;&nbsp;</label>
                                        @if($loop->first)
                                            <button type="button" class="btn btn-sm btn-outline-secondary w-100" disabled title="Tier pertama tidak dapat dihapus">
                                                <i class="ti ti-lock ti-sm"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-tarif w-100" title="Hapus tier ini">
                                                <i class="ti ti-x ti-sm"></i>
                                            </button>
                                        @endif
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

            // Initialize existing data for edit mode
            initializeEditMode();

            function initializeEditMode() {
                // Count existing tiers and update counter
                updateRemoveButtons();
                updateFormState();
            }

            // Isi tier pertama otomatis (untuk edit mode, tidak otomatis karena data sudah ada)
            function setTierPertama() {
                // Untuk edit mode, tier pertama sudah ada dari database
                // Hanya update jika container kosong
                if ($('.tarif-row').length === 0) {
                    let tarifMinimum = parseFloat($('#tarif_minimum').val()) || 0;
                    let tarifPerKm = parseFloat($('#tarif_per_km').val()) || 0;
                    let maxDistance = tarifPerKm > 0 ? Math.floor(tarifMinimum / tarifPerKm) : '';
                    let tarifIndex = 0;

                    if (tarifMinimum > 0 && tarifPerKm > 0 && maxDistance > 0) {
                        $('#tarifContainer').html(`
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <h6 class="alert-heading fw-bold mb-1">Tier 1 (Otomatis)</h6>
                                <p class="mb-0">Tier pertama dihitung otomatis berdasarkan tarif minimum dan tarif per KM</p>
                            </div>
                            <div class="tarif-row row g-3 mb-3" data-tier="1">
                                <div class="col-md-3">
                                    <label class="form-label">Jarak Minimal (KM)</label>
                                    <input type="number" name="tarifs[${tarifIndex}][min_distance]" class="form-control bg-light" value="1" min="1" required readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Jarak Maksimal (KM)</label>
                                    <input type="number" name="tarifs[${tarifIndex}][max_distance]" class="form-control bg-light" value="${maxDistance}" min="1" required readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tarif (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="tarifs[${tarifIndex}][tarif]" class="form-control bg-light" value="${tarifPerKm}" min="0" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Tier pertama tidak dapat dihapus">
                                        <i class="ti ti-lock ti-sm"></i>
                                    </button>
                                </div>
                            </div>
                        `);
                        updateRemoveButtons();
                        resetTarifIndex();
                    }
                }
            }

            // Untuk edit mode, tarif_per_km disabled, jadi tidak perlu event listener
            // $('#tarif_minimum, #tarif_per_km').on('input', setTierPertama);

            // Add new tarif row (max 3 tier)
            let tarifIndex = {{ count(old('tarifs', $ambulanceType->tarifs)) }};
            
            function resetTarifIndex() {
                tarifIndex = $('.tarif-row').length;
            }
            
            function getNextMinDistance() {
                const $rows = $('.tarif-row');
                if ($rows.length === 0) return 1;
                
                let maxDistance = 0;
                $rows.each(function() {
                    const rowMaxDistance = parseInt($(this).find('[name*="max_distance"]').val()) || 0;
                    if (rowMaxDistance > maxDistance) {
                        maxDistance = rowMaxDistance;
                    }
                });
                return maxDistance + 1;
            }
            
            function validateDistanceInputs($row) {
                const minDistance = parseInt($row.find('[name*="min_distance"]').val()) || 0;
                const maxDistance = parseInt($row.find('[name*="max_distance"]').val()) || 0;
                
                // Clear previous validation states
                $row.find('[name*="min_distance"], [name*="max_distance"]').removeClass('is-invalid is-valid');
                $row.find('.invalid-feedback').remove();
                
                let isValid = true;
                let hasOverlap = false;
                
                // Validate min < max
                if (minDistance >= maxDistance && maxDistance > 0) {
                    $row.find('[name*="max_distance"]').addClass('is-invalid').after('<div class="invalid-feedback">Jarak maksimal harus lebih besar dari jarak minimal</div>');
                    isValid = false;
                }
                
                // Check overlap with other tiers
                $('.tarif-row').not($row).each(function() {
                    const otherMin = parseInt($(this).find('[name*="min_distance"]').val()) || 0;
                    const otherMax = parseInt($(this).find('[name*="max_distance"]').val()) || 0;
                    
                    if ((minDistance >= otherMin && minDistance <= otherMax) || 
                        (maxDistance >= otherMin && maxDistance <= otherMax) ||
                        (minDistance <= otherMin && maxDistance >= otherMax)) {
                        $row.find('[name*="min_distance"], [name*="max_distance"]').addClass('is-invalid');
                        if (!$row.find('.invalid-feedback').length) {
                            $row.find('[name*="max_distance"]').after('<div class="invalid-feedback">Jarak tidak boleh overlap dengan tier lain</div>');
                        }
                        isValid = false;
                        hasOverlap = true;
                    }
                });
                
                if (isValid && minDistance > 0 && maxDistance > 0) {
                    $row.find('[name*="min_distance"], [name*="max_distance"]').addClass('is-valid');
                }
                
                // Update form state based on validation
                updateFormState();
                
                return isValid;
            }
            
            $('#addTarif').click(function() {
                const $rows = $('.tarif-row');
                if ($rows.length >= 3) return; // max 3 tier
                
                const nextMinDistance = getNextMinDistance();
                const tierNumber = $rows.length + 1;

                const newRow = `
                    <div class="tarif-row row g-3 mb-3" data-tier="${tierNumber}">
                        <div class="col-md-3">
                            <label class="form-label">Jarak Minimal (KM) <small class="text-muted">Tier ${tierNumber}</small></label>
                            <input type="number" name="tarifs[${tarifIndex}][min_distance]" class="form-control" value="${nextMinDistance}" min="${nextMinDistance}" required>
                            <small class="text-muted">Minimal: ${nextMinDistance} KM</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jarak Maksimal (KM)</label>
                            <input type="number" name="tarifs[${tarifIndex}][max_distance]" class="form-control" min="${nextMinDistance + 1}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tarif (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="tarifs[${tarifIndex}][tarif]" class="form-control" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-sm btn-danger btn-remove-tarif w-100" title="Hapus tier ini">
                                <i class="ti ti-x ti-sm"></i>
                            </button>
                        </div>
                    </div>`;
                $('#tarifContainer').append(newRow);
                tarifIndex++;
                updateRemoveButtons();
                updateTierLabels();
            });
            
            // Validate on input change
            $(document).on('input', '[name*="min_distance"], [name*="max_distance"]', function() {
                const $row = $(this).closest('.tarif-row');
                validateDistanceInputs($row);
            });
            
            function updateTierLabels() {
                $('.tarif-row').each(function(index) {
                    const tierNumber = index + 1;
                    $(this).attr('data-tier', tierNumber);
                    $(this).find('label:first small').text(`Tier ${tierNumber}`);
                });
            }

            // Update form state based on validation errors
            function updateFormState() {
                const hasInvalidInputs = $('.is-invalid').length > 0;
                
                // Only disable submit button if there are validation errors (not empty inputs)
                // Disable/enable submit button
                $('button[type="submit"]').prop('disabled', hasInvalidInputs);
                
                // Update submit button appearance
                if (hasInvalidInputs) {
                    $('button[type="submit"]')
                        .removeClass('btn-primary')
                        .addClass('btn-secondary')
                        .attr('title', 'Harap perbaiki error sebelum menyimpan');
                } else {
                    $('button[type="submit"]')
                        .removeClass('btn-secondary')
                        .addClass('btn-primary')
                        .removeAttr('title');
                }
                // Disable/enable other inputs when there are validation errors
                if (hasInvalidInputs) {
                    // Disable vehicle select and tarif minimum (tarif_per_km sudah disabled)
                    $('#vehicle_id, #tarif_minimum').prop('disabled', true);
                    $('#addTarif').prop('disabled', true);
                    
                    // Disable add new tier
                    $('#addTarif').html('<i class="bx bx-exclamation-triangle"></i> Perbaiki Error Dulu')
                        .removeClass('btn-primary btn-secondary')
                        .addClass('btn-warning');
                } else {
                    // Re-enable inputs (tarif_per_km tetap disabled)
                    $('#vehicle_id, #tarif_minimum').prop('disabled', false);
                    
                    // Reset add tarif button
                    const $rows = $('.tarif-row');
                    if ($rows.length >= 3) {
                        $('#addTarif').html('<i class="bx bx-check"></i> Maksimal 3 Tier')
                            .removeClass('btn-primary btn-warning')
                            .addClass('btn-secondary')
                            .prop('disabled', true);
                    } else {
                        $('#addTarif').html('<i class="bx bx-plus"></i> Tambah Tarif')
                            .removeClass('btn-secondary btn-warning')
                            .addClass('btn-primary')
                            .prop('disabled', false);
                    }
                }
            }

            // Remove tarif row
            $(document).on('click', '.btn-remove-tarif:not([disabled])', function() {
                if (confirm('Apakah Anda yakin ingin menghapus tier ini?')) {
                    $(this).closest('.tarif-row').remove();
                    updateRemoveButtons();
                    updateTierLabels();
                    updateFormState(); // Revalidate form after removal
                }
            });

            // Validate on input change
            $(document).on('input', '[name*="min_distance"], [name*="max_distance"], [name*="tarif"]', function() {
                const $row = $(this).closest('.tarif-row');
                validateDistanceInputs($row);
            });

            // Validate tarif minimum and vehicle inputs (tarif_per_km disabled di edit mode)
            $('#tarif_minimum, #vehicle_id').on('input change', function() {
                updateFormState();
            });

            // Show/hide remove button based on number of rows
            function updateRemoveButtons() {
                const $rows = $('.tarif-row');
                $('.btn-remove-tarif').each(function(i, btn) {
                    $(btn).prop('disabled', i === 0); // tier pertama tidak bisa dihapus
                });
                $('#addTarif').prop('disabled', $rows.length >= 3);
                
                // Update button text and state
                if ($rows.length >= 3) {
                    $('#addTarif').html('<i class="bx bx-check"></i> Maksimal 3 Tier').addClass('btn-secondary').removeClass('btn-primary');
                } else {
                    $('#addTarif').html('<i class="bx bx-plus"></i> Tambah Tarif').addClass('btn-primary').removeClass('btn-secondary');
                }

                // Update tier counter
                $('#tierCounter').text(`${$rows.length} dari 3 Tier`);
                
                // Update badge color based on tier count
                const $badge = $('#tierCounter');
                $badge.removeClass('bg-info bg-warning bg-success');
                if ($rows.length === 0) {
                    $badge.addClass('bg-info');
                } else if ($rows.length < 3) {
                    $badge.addClass('bg-warning');
                } else {
                    $badge.addClass('bg-success');
                }
            }

            // Form validation before submit
            $('#form').on('submit', function(e) {
                let isValid = true;
                let errorMessages = [];

                // Check for validation errors in form
                if ($('.is-invalid').length > 0) {
                    errorMessages.push('Harap perbaiki error pada form sebelum menyimpan');
                    isValid = false;
                }

                // Validate all tarif rows
                $('.tarif-row').each(function() {
                    if (!validateDistanceInputs($(this))) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    if (errorMessages.length > 0) {
                        alert('Error:\n' + errorMessages.join('\n'));
                    }
                    return false;
                }
            });
        });
    </script>
@endpush