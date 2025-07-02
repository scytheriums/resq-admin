@extends('layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / Pengaturan /</span> Tambah Baru
    </h4>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Tambah Pengaturan Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="key" class="form-label">Kunci <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key') }}" required>
                        @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="value" class="form-label">Nilai <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('value') is-invalid @enderror" id="value" name="value" rows="3">{{ old('value') }}</textarea>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class='bx bx-save me-1'></i> Simpan Pengaturan
                    </button>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-label-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
