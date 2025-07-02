@extends('layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / Hak Akses /</span> Tambah Hak Akses
    </h4>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Tambah Hak Akses</h5>
        </div>
        <div class="card-body">
            <form id="form" action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Nama Hak Akses</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" 
                            placeholder="Masukkan nama hak akses" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-end gap-1 p-3 border-top">
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Batal</a>
            <button type="submit" form="form" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
        </div>
    </div>
</div>
@endsection
