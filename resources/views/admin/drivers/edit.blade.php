@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> {{ $title }}
    </h4>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.drivers.update', $driver) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $driver->name) }}" placeholder="Enter driver's full name" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone_number">Phone</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $driver->phone_number) }}" placeholder="Enter driver's phone number" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="telegram_chat_id">Telegram Chat ID</label>
                            <input type="text" class="form-control" id="telegram_chat_id" name="telegram_chat_id" value="{{ old('telegram_chat_id', $driver->telegram_chat_id) }}" placeholder="Enter Telegram Chat ID" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="license_plate">License Plate</label>
                            <input type="text" class="form-control" id="license_plate" name="license_plate" value="{{ old('license_plate', $driver->license_plate) }}" placeholder="Enter vehicle license plate number" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="ambulance_type_id">Ambulance Type</label>
                            <select class="form-select" id="ambulance_type_id" name="ambulance_type_id" required>
                                <option value="">Select Ambulance Type</option>
                                @foreach($ambulanceTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('ambulance_type_id', $driver->ambulance_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="base_address">Base Address</label>
                            <textarea class="form-control" id="base_address" rows="5" name="base_address" placeholder="Enter the base address of the ambulance" required>{{ old('base_address', $driver->base_address) }}</textarea>
                            <input type="hidden" class="form-control" id="base_latitude" name="base_latitude" value="{{ old('base_latitude', $driver->base_latitude) }}" required readonly />
                            <input type="hidden" class="form-control" id="base_longitude" name="base_longitude" value="{{ old('base_longitude', $driver->base_longitude) }}" required readonly />
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Driver</button>
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
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
            $('#ambulance_type_id').select2({
                placeholder: 'Select Ambulance Type',
                allowClear: true
            });
        });
    </script>
@endpush
