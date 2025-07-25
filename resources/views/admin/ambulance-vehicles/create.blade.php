@extends('layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <a href="{{ route('admin.ambulance-vehicles.index') }}" class="text-muted fw-light">Admin /</a>
        <a href="{{ route('admin.ambulance-vehicles.index') }}" class="text-muted fw-light">Kendaraan Ambulance /</a>
        Tambah Baru
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Tambah Kendaraan Ambulance</h5>
        </div>
        <div class="card-body">
            <form id="ambulance-vehicle-form" action="{{ route('admin.ambulance-vehicles.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="vehicle_name">Nama Kendaraan <span class="text-danger">*</span></label>
                        <input type="text" 
                            class="form-control @error('vehicle_name') is-invalid @enderror" 
                            id="vehicle_name" 
                            name="vehicle_name" 
                            value="{{ old('vehicle_name') }}" 
                            placeholder="Masukkan nama kendaraan" 
                            required>
                        <div class="invalid-feedback">
                            @error('vehicle_name') {{ $message }} @else Nama kendaraan wajib diisi @enderror
                        </div>
                    </div>

                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.ambulance-vehicles.index') }}" class="btn btn-outline-secondary me-2">
                        <i class='bx bx-arrow-back me-1'></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save me-1'></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
