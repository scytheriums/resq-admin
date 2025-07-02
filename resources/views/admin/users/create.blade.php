@extends('layouts.master')

@section('vendor-css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / Pengguna /</span> Tambah Pengguna
    </h4>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Tambah Pengguna</h5>
        </div>
        <div class="card-body">
            <form id="form" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" 
                            placeholder="Masukkan nama pengguna" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email') }}" 
                            placeholder="Masukkan email pengguna" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password" placeholder="Masukkan password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" 
                            id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="roles" class="form-label">Peran</label>
                        <select class="form-control select2 @error('roles') is-invalid @enderror" id="roles" name="roles[]" multiple="multiple">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-end gap-1 p-3 border-top">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Batal</a>
            <button type="submit" form="form" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
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
        $('#roles').select2({
            placeholder: 'Pilih Peran',
            allowClear: true
        });
    });
</script>
@endpush
