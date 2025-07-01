@extends('layouts.master')

@section('title', $title)

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> {{ $title }}
    </h4>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.drivers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="photo">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="license_number">License Number</label>
                            <input type="text" class="form-control" id="license_number" name="license_number" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="license_expiry_date">License Expiry Date</label>
                            <input type="date" class="form-control" id="license_expiry_date" name="license_expiry_date" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="license_photo">License Photo</label>
                            <input type="file" class="form-control" id="license_photo" name="license_photo" />
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="vehicle_number">Vehicle Number</label>
                            <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="vehicle_type">Vehicle Type</label>
                            <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="vehicle_photo">Vehicle Photo</label>
                            <input type="file" class="form-control" id="vehicle_photo" name="vehicle_photo" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="vehicle_brand">Vehicle Brand</label>
                            <input type="text" class="form-control" id="vehicle_brand" name="vehicle_brand" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="vehicle_model">Vehicle Model</label>
                            <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="available">Available</option>
                                <option value="on_duty">On Duty</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Driver</button>
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            const fv = $('#drivers-form').formValidation({
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Name is required'
                            },
                            stringLength: {
                                max: 255,
                                message: 'Name must be less than 255 characters'
                            }
                        }
                    },
                    phone: {
                        validators: {
                            notEmpty: {
                                message: 'Phone is required'
                            },
                            stringLength: {
                                max: 20,
                                message: 'Phone must be less than 20 characters'
                            }
                        }
                    },
                    email: {
                        validators: {
                            emailAddress: {
                                message: 'Invalid email address'
                            }
                        }
                    },
                    license_number: {
                        validators: {
                            notEmpty: {
                                message: 'License number is required'
                            }
                        }
                    },
                    license_expiry_date: {
                        validators: {
                            notEmpty: {
                                message: 'License expiry date is required'
                            }
                        }
                    },
                    vehicle_number: {
                        validators: {
                            notEmpty: {
                                message: 'Vehicle number is required'
                            }
                        }
                    },
                    vehicle_type: {
                        validators: {
                            notEmpty: {
                                message: 'Vehicle type is required'
                            }
                        }
                    },
                    vehicle_brand: {
                        validators: {
                            notEmpty: {
                                message: 'Vehicle brand is required'
                            }
                        }
                    },
                    vehicle_model: {
                        validators: {
                            notEmpty: {
                                message: 'Vehicle model is required'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
