@extends('layouts.master')

@section('title', 'Edit Tujuan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / Tujuan /</span> Edit
    </h4>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Edit Tujuan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.destinations.update', $destination) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $destination->name) }}" placeholder="Enter destination name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Enter full address" required>{{ old('address', $destination->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <input type="hidden" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $destination->latitude) }}" placeholder="e.g. -6.2088" required>
                        <input type="hidden" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $destination->longitude) }}" placeholder="e.g. 106.8456" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="province" class="form-label">Provinsi</label>
                        <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province', $destination->province) }}" placeholder="e.g. DKI Jakarta" required>
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label">Kota/Kabupaten</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $destination->city) }}" placeholder="e.g. Jakarta Selatan" required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="district" class="form-label">Kecamatan</label>
                        <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district', $destination->district) }}" placeholder="e.g. Setiabudi" required>
                        @error('district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="subdistrict" class="form-label">Kelurahan/Desa</label>
                        <input type="text" class="form-control @error('subdistrict') is-invalid @enderror" id="subdistrict" name="subdistrict" value="{{ old('subdistrict', $destination->subdistrict) }}" placeholder="e.g. Karet Kuningan" required>
                        @error('subdistrict')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code', $destination->postal_code) }}" placeholder="e.g. 12920" required>
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="is_default" class="form-label">Jadikan default ?</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default" {{ old('is_default', $destination->is_default) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Ya</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Perbarui Tujuan</button>
                    <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
