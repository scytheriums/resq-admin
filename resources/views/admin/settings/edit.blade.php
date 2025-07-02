@extends('layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / Pengaturan /</span> Edit Pengaturan
    </h4>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Edit Pengaturan: {{ $setting->getAttributes()['key'] }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update', $setting->getAttributes()['key']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="key" class="form-label">Kunci <span class="text-muted">(readonly)</span></label>
                        <input type="text" class="form-control-plaintext" id="key" value="{{ $setting->getAttributes()['key'] }}" readonly>
                        <input type="hidden" name="key" value="{{ $setting->getAttributes()['key'] }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="value" class="form-label">Nilai <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('value') is-invalid @enderror" id="value" name="value" rows="3" required>{{ old('value', $setting->value) }}</textarea>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $setting->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save me-1'></i> Perbarui Pengaturan
                    </button>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                        <i class='bx bx-x me-1'></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
