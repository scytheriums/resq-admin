@extends('layouts.master')

@section('title', $title)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / Layanan Tambahan /</span> Edit Layanan Tambahan
    </h4>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Edit Layanan Tambahan</h5>
        </div>
        <div class="card-body">
            <form id="form" action="{{ route('admin.additional-services.update', $additionalService) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Layanan</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $additionalService->name) }}" placeholder="Masukkan nama layanan tambahan" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $additionalService->price) }}" placeholder="Masukkan harga layanan" required step="0.01">
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-end gap-1 p-3 border-top">
            <a href="{{ route('admin.additional-services.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Batal</a>
            <button type="submit" form="form" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Perubahan</button>
        </div>
    </div>
</div>
@endsection
