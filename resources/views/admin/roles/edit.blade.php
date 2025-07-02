@extends('layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / Peran /</span> Edit Peran
    </h4>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Edit Peran</h5>
        </div>
        <div class="card-body">
            <form id="form" action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Nama Peran</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name', $role->name) }}" 
                            placeholder="Masukkan nama peran" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <label class="form-label">Hak Akses</label>
                    @foreach($permissions as $group => $groupPermissions)
                        <div class="col-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">{{ ucfirst($group) }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-end gap-1 p-3 border-top">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Batal</a>
            <button type="submit" form="form" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Perubahan</button>
        </div>
    </div>
</div>
@endsection
