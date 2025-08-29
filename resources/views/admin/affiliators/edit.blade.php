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
            <form action="{{ route('admin.affiliators.update', $affiliator) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $affiliator->name) }}" placeholder="Enter driver's full name" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="code">Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $affiliator->code) }}" placeholder="Enter affiliator code" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $affiliator->email) }}" placeholder="Enter affiliator email" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone_number">Phone</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $affiliator->phone_number) }}" placeholder="Enter affiliator phone number" required />
                        </div>
                       
                        <div class="mb-3">
                            <label class="form-label" for="full_address">Base Address</label>
                            <textarea class="form-control" id="full_address" rows="5" name="full_address" placeholder="Enter the base address of the affiliator" required>{{ old('full_address', $affiliator->full_address) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                        <div class="mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked($affiliator->is_active)>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Affiliator</button>
                    <a href="{{ route('admin.affiliators.index') }}" class="btn btn-secondary">Cancel</a>
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
            const provinceCode = '{{ $affiliator->province_code }}';
            const cityCode = '{{ $affiliator->city_code }}';
            const districtCode = '{{ $affiliator->district_code }}';
            const villageCode = '{{ $affiliator->village_code }}';

            if (provinceCode) {
                let option = new Option("{{ $affiliator->province?->name }}", provinceCode, true, true);
                $('#province_code').append(option).trigger('change');
            }

            if (cityCode) {
                let option = new Option("{{ $affiliator->city?->name }}", cityCode, true, true);
                $('#city_code').append(option).trigger('change');
            }

            if (districtCode) {
                let option = new Option("{{ $affiliator->district?->name }}", districtCode, true, true);
                $('#district_code').append(option).trigger('change');
            }

            if (villageCode) {
                let option = new Option("{{ $affiliator->village?->name }}", villageCode, true, true);
                $('#village_code').append(option).trigger('change');
            }
        });
    </script>
@endpush
