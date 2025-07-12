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
                        <div class="mb-3">
                            <label class="form-label" for="base_address">Base Address</label>
                            <textarea class="form-control" id="base_address" rows="5" name="base_address" placeholder="Enter the base address of the ambulance" required>{{ old('base_address', $driver->base_address) }}</textarea>
                            <input type="hidden" class="form-control" id="base_latitude" name="base_latitude" value="{{ old('base_latitude', $driver->base_latitude) }}" required readonly />
                            <input type="hidden" class="form-control" id="base_longitude" name="base_longitude" value="{{ old('base_longitude', $driver->base_longitude) }}" required readonly />
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
                            <label class="form-label" for="province_code">Province</label>
                            <select class="form-select select2-ajax" id="province_code" name="province_code" required>
                                <option value="">Select Province</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="city_code">City</label>
                            <select class="form-select select2-ajax" id="city_code" name="city_code" required>
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="district_code">District</label>
                            <select class="form-select select2-ajax" id="district_code" name="district_code" required>
                                <option value="">Select District</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="village_code">Village</label>
                            <select class="form-select select2-ajax" id="village_code" name="village_code" required>
                                <option value="">Select Village</option>
                            </select>
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
            // Initialize province select2
            $('#province_code').select2({
                placeholder: 'Select Province',
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.drivers.get-provinces') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {
                                    id: item.code,
                                    text: item.name
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // Initialize city select2
            $('#city_code').select2({
                placeholder: 'Select City',
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.drivers.get-cities') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            province_code: $('#province_code').val()
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {
                                    id: item.code,
                                    text: item.name
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // Initialize district select2
            $('#district_code').select2({
                placeholder: 'Select District',
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.drivers.get-districts') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            city_code: $('#city_code').val()
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {
                                    id: item.code,
                                    text: item.name
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // Initialize village select2
            $('#village_code').select2({
                placeholder: 'Select Village',
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.drivers.get-villages') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            district_code: $('#district_code').val()
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {
                                    id: item.code,
                                    text: item.name
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // Handle cascading dropdowns
            $('#province_code').on('change', function() {
                $('#city_code').val(null).trigger('change');
                $('#district_code').val(null).trigger('change');
                $('#village_code').val(null).trigger('change');
            });

            $('#city_code').on('change', function() {
                $('#district_code').val(null).trigger('change');
                $('#village_code').val(null).trigger('change');
            });

            $('#district_code').on('change', function() {
                $('#village_code').val(null).trigger('change');
            });

            // Initialize ambulance type select2
            $('#ambulance_type_id').select2({
                placeholder: 'Select Ambulance Type',
                allowClear: true
            });

            // Set initial values for cascading selects
            const provinceCode = '{{ $driver->province_code }}';
            const cityCode = '{{ $driver->city_code }}';
            const districtCode = '{{ $driver->district_code }}';
            const villageCode = '{{ $driver->village_code }}';

            if (provinceCode) {
                let option = new Option("{{ $driver->province->name }}", provinceCode, true, true);
                $('#province_code').append(option).trigger('change');
            }

            if (cityCode) {
                let option = new Option("{{ $driver->city->name }}", cityCode, true, true);
                $('#city_code').append(option).trigger('change');
            }

            if (districtCode) {
                let option = new Option("{{ $driver->district->name }}", districtCode, true, true);
                $('#district_code').append(option).trigger('change');
            }

            if (villageCode) {
                let option = new Option("{{ $driver->village->name }}", villageCode, true, true);
                $('#village_code').append(option).trigger('change');
            }
        });
    </script>
@endpush
