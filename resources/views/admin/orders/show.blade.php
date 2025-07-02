@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/intro.js@7.2.0/minified/introjs.min.css">
    <style>
        .info-label {
            color: #697a8d; /* Subdued text color */
        }
        .info-value {
            font-weight: 600; /* Bold value */
        }
        .map-container {
            height: 250px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .card-header h5 {
            margin-bottom: 0;
        }
        .introjs-helperLayer {
            border: 2px solid #435ebe;
            border-radius: 0.5rem;
        }
        .introjs-tooltip {
            min-width: 300px;
            max-width: 400px;
        }
    </style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Admin / Pesanan /</span> Detail #{{ $order->order_number }}
        </h4>
        <div>
            @if ($order->order_status === 'in_progress_deliver')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#completeOrderModal">
                    <i class='bx bx-edit me-1'></i> Selesaikan Pesanan
                </button>
            @endif
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class='bx bx-arrow-back me-1'></i> Kembali
            </a>
        </div>
    </div>

    <div class="modal fade" id="completeOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Selesaikan Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyelesaikan pesanan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="{{ route('admin.orders.complete', $order->id) }}" class="btn btn-primary">Ya, Selesaikan</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-7 col-xl-8">

            {{-- Review Section --}}
            @if ($order->review)
                <div class="modal fade modal-danger" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h5 class="text-center mb-1" id="deleteModalTitle">Apakah Anda Yakin Menghapus Ulasan?</h5>
                        <div class="text-center mt-2">
                            Apakah Anda yakin ingin menghapus ulasan ini ? 
                            <br>
                            Tindakan ini tidak dapat dibatalkan.
                        </div>
                
                        <form class="row gy-1 gx-2 mt-75" method="post" action="{{ route('admin.orders.review.delete', $order->id) }}">
                            @method('DELETE')
                            @csrf
                            <div class="col-12 text-center">
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Batal">Batal</button>
                            <button type="submit" class="btn btn-danger me-1 mt-1 btn-confirm-delete">Hapus</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>
                </div>
                @php
                    $rating = $order->review->rating;
                    $colorClass = 'alert-secondary'; // Default
                    if ($rating <= 1) $colorClass = 'alert-danger';
                    else if ($rating == 2) $colorClass = 'alert-warning';
                    else if ($rating == 3) $colorClass = 'alert-info';
                    else if ($rating == 4) $colorClass = 'alert-success';
                    else if ($rating >= 5) $colorClass = 'alert-success';
                @endphp
                <div class="alert {{ $colorClass }}" role="alert">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="alert-heading">Ulasan & Rating</h5>
                        <button 
                            type="button" class="btn btn-icon btn-outline-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteCommentModal" 
                        > 
                            <i class="ti ti-trash ti-sm mx-2"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="ti ti-star-filled {{ $i <= $rating ? 'text-warning' : 'text-secondary' }}"></i>
                        @endfor
                        <span class="ms-2 fw-bold">({{ $rating }}/5)</span>
                    </div>
                    <p class="mb-0">{{ $order->review->comment }}</p>
                    <hr>
                    <p class="mb-0 small text-muted">Diulas oleh {{ $order->user->name }} pada {{ $order->review->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
            @endif
            <!-- Order & Customer Details -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">A. Informasi Pesanan & Pelanggan</h4>
                    <x-status-badge status="{{ $order->order_status }}" />
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <p class="info-label mb-1">No. Pesanan</p>
                            <p class="info-value mb-0">{{ $order->order_number }}</p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <p class="info-label mb-1">Tanggal Pesanan</p>
                            <p class="info-value mb-0">{{ $order->order_date->translatedFormat('l, d F Y, H:i') }}</p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <p class="info-label mb-1">Nama Pelanggan</p>
                            <p class="info-value mb-0">{{ $order->name }}</p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <p class="info-label mb-1">Nomor WhatsApp</p>
                            <p class="info-value mb-0">{{ $order->whatsapp_number }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <p class="info-label mb-1">Status Pesanan</p>
                            <p class="info-value mb-0"><x-status-badge class="{{ $order->order_status_class }}" label="{{ $order->order_status_label }}" /></p>
                        </div>
                        <div class="col-6 mb-3">
                            <p class="info-label mb-1">Status Pembayaran</p>
                            <p class="info-value mb-0"><x-status-badge class="{{ $order->payment_status_class }}" label="{{ $order->payment_status_label }}" /></p>
                        </div>
                        <div class="col-12">
                            <p class="info-label mb-1">Catatan</p>
                            <p class="info-value mb-0">{{ $order->notes }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">B. Informasi Lokasi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Pickup Location -->
                        <div class="col-md-6">
                            <h6>Lokasi Penjemputan</h6>
                            <p class="mb-1">{{ $order->pickup_address }}</p>
                            <small class="text-muted">
                                {{ $order->pickup_subdistrict }}, {{ $order->pickup_district }}, {{ $order->pickup_city }}, {{ $order->pickup_province }} {{ $order->pickup_postal_code }}
                            </small>
                        </div>
                        <!-- Destination Location -->
                        <div class="col-md-6">
                            <h6>Lokasi Tujuan</h6>
                            <p class="mb-1">{{ $order->destination_address }}</p>
                            <small class="text-muted">
                                {{ $order->destination_subdistrict }}, {{ $order->destination_district }}, {{ $order->destination_city }}, {{ $order->destination_province }} {{ $order->destination_postal_code }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <div id="pickupMap" class="map-container mt-3"></div>
                        </div>
                        <div class="col-md-6">
                            <div id="destinationMap" class="map-container mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div id="order-actions-section" 
             class="col-lg-5 col-xl-4" 
             @if(in_array($order->order_status, ['created', 'booked']))
                data-intro="<h5>Kelola Pesanan</h5><p>Di sini Anda dapat mengatur driver dan layanan tambahan untuk pesanan ini.</p>"
                data-step="1"
             @endif>
            <form id="updateOrderForm" action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Service & Driver Details Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">C. Layanan & Driver</h5>
                    </div>
                    <div class="card-body">
                        <!-- Static Service Details -->
                        <div class="mb-3">
                            <p class="info-label mb-1">Jenis Layanan</p>
                            <p class="info-value mb-0">{{ ucfirst($order->service_type) }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="info-label mb-1">Tipe Ambulans</p>
                            <p class="info-value mb-0">{{ $order->ambulanceType->name ?? '-' }}</p>
                        </div>
                        <div class="mb-3" 
                             @if(in_array($order->order_status, ['created', 'booked']))
                                data-intro="<h5>Tujuan Penggunaan</h5><p>Tujuan penggunaan layanan ini.</p>"
                                data-step="2"
                             @endif>
                            <p class="info-label mb-1">Tujuan Penggunaan</p>
                            <p class="info-value mb-0">{{ $order->purpose->name ?? '-' }}</p>
                        </div>

                        <!-- Editable Driver -->
                        <div class="mb-3" 
                             @if(in_array($order->order_status, ['created', 'booked']))
                                data-intro="<h5>Pilih Driver</h5><p>Pilih driver yang tersedia untuk menangani pesanan ini. Pastikan driver yang dipilih sesuai dengan zona dan ketersediaannya.</p>"
                                data-step="2"
                             @endif>
                            <label for="driverSelect" class="form-label">Driver</label>
                            @if (!in_array($order->order_status, ['created', 'booked']) && $order->driver)
                                <p class="info-value mb-0">{{ $order->driver->name }} ({{ $order->driver->phone_number }})</p>
                                <small class="text-muted">Driver tidak dapat diubah setelah pesanan diproses.</small>
                            @else
                                <select class="form-select @error('driver_id') is-invalid @enderror" id="driverSelect" name="driver_id">
                                    <option value="">Pilih Driver</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ $order->driver_id == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->name }} ({{ $driver->phone_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <!-- Editable Additional Services -->
                        <div @if(in_array($order->order_status, ['created', 'booked']))
                                data-intro="<h5>Layanan Tambahan</h5><p>Pilih layanan tambahan yang diperlukan. Harga akan otomatis diperbarui di rincian pembayaran.</p>"
                                data-step="3"
                             @endif>
                            <label for="additionalServices" class="form-label">Layanan Tambahan</label>
                            <select class="select2 form-select" id="additionalServices" name="additional_services[]" multiple>
                                @foreach($additionalServices as $service)
                                    <option value="{{ $service->id }}" 
                                        data-price="{{ $service->price }}"
                                        {{ in_array($service->id, $order->additionalServices->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Payment Details Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">D. Rincian Pembayaran</h5>
                        <x-status-badge status="{{ $order->payment_status }}" />
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="info-label">Harga Dasar</span>
                            <span class="info-value">{{ 'Rp' . number_format($order->base_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="info-label">Biaya Booking</span>
                            <span class="info-value">{{ 'Rp' . number_format($order->booking_fee, 0, ',', '.') }}</span>
                        </div>
                        
                        <!-- Additional Services Breakdown -->
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="info-label">Layanan Tambahan</span>
                                <span class="info-value" id="additionalServicesFee">Rp0</span>
                            </div>
                            <div id="additionalServicesBreakdown" class="ps-3"></div>
                        </div>

                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Total Tagihan</h6>
                            <h6 class="mb-0" id="totalBill">Rp0</h6>
                        </div>
                    </div>
                </div>

                @if (in_array($order->order_status, ['created', 'booked']))
                    <button type="submit" 
                            id="submitBtn" 
                            class="btn btn-primary w-100"
                            data-intro="<h5>Simpan Perubahan</h5><p>Klik tombol ini untuk menyimpan semua perubahan yang telah Anda buat.</p>"
                            data-step="4">
                        Simpan & Proses Pesanan
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://unpkg.com/intro.js@7.2.0/minified/intro.min.js"></script>
    <script>
        // Initialize intro.js if order status is created
        document.addEventListener('DOMContentLoaded', function() {
            @if(in_array($order->order_status, ['created', 'booked']))
                // Check if we should show the intro
                const showIntro = localStorage.getItem('orderTourShown') !== 'true';
                
                // Initialize intro.js
                const intro = introJs();
                intro.setOptions({
                    nextLabel: 'Selanjutnya',
                    prevLabel: 'Sebelumnya',
                    doneLabel: 'Selesai',
                    skipLabel: 'Lewati',
                    exitOnOverlayClick: false,
                    showStepNumbers: true,
                    showBullets: false,
                    showProgress: true,
                    disableInteraction: true
                });

                // Start the tour if it hasn't been shown before
                if (showIntro) {
                    setTimeout(() => {
                        intro.start();
                        localStorage.setItem('orderTourShown', 'true');
                    }, 1000);
                }

                // Add a help button to restart the tour
                const helpButton = document.createElement('button');
                helpButton.setAttribute('type', 'button');
                helpButton.className = 'btn btn-outline-primary btn-sm ms-2';
                helpButton.innerHTML = '<i class="bx bx-help-circle"></i> Bantuan';
                helpButton.onclick = function() {
                    intro.start();
                };
                
                const cardHeader = document.querySelector('#order-actions-section .card-header');
                if (cardHeader) {
                    cardHeader.appendChild(helpButton);
                }
            @endif
        });
        $(document).ready(function() {
            $('#driverSelect').select2({ placeholder: 'Pilih Driver' });
            $('#additionalServices').select2({ placeholder: 'Pilih layanan tambahan', allowClear: true });

            const basePrice = {{ $order->base_price ?? 0 }};
            const bookingFee = {{ $order->booking_fee ?? 0 }};

            function formatCurrency(value) {
                return 'Rp' + new Intl.NumberFormat('id-ID').format(value);
            }

            function updatePriceSummary() {
                let servicesFee = 0;
                let breakdownHtml = '';

                $('#additionalServices option:selected').each(function() {
                    const price = parseFloat($(this).data('price'));
                    const name = $(this).text().trim();
                    servicesFee += price;
                    breakdownHtml += `
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">• ${name}</small>
                            <small class="text-muted">${formatCurrency(price)}</small>
                        </div>`;
                });

                if (breakdownHtml === '') {
                    breakdownHtml = '<small class="text-muted">Tidak ada layanan tambahan</small>';
                }

                const totalBill = basePrice + bookingFee + servicesFee;

                $('#additionalServicesFee').text(formatCurrency(servicesFee));
                $('#additionalServicesBreakdown').html(breakdownHtml);
                $('#totalBill').text(formatCurrency(totalBill));
            }

            $('#additionalServices').on('change', updatePriceSummary);

            // Close intro.js if it's open when form is submitted
            if (window.introJs) {
                introJs().exit();
            }
            
            $('#updateOrderForm').on('submit', function(e) {
                // e.preventDefault();
                const form = $(this);
                const submitBtn = $('#submitBtn');
                const originalBtnText = submitBtn.html();

                submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...').prop('disabled', true);

                // $.ajax({
                //     url: form.attr('action'),
                //     method: 'POST', // Form method is POST, with _method hidden input for PUT
                //     data: form.serialize(),
                //     success: function(response) {
                //         toastr.success('Perubahan berhasil disimpan!');
                //         $('#additionalServicesFee').text(formatCurrency(response.data.additional_services_fee));
                //         $('#totalBill').text(formatCurrency(response.data.total_bill));
                //     },
                //     error: function(xhr) {
                //         toastr.error('Gagal menyimpan perubahan. Silakan coba lagi.');
                //         console.error(xhr.responseText);
                //     },
                //     complete: function() {
                //         submitBtn.html(originalBtnText).prop('disabled', false);
                //     }
                // });
            });

            // --- Initial Calculation on Page Load ---
            updatePriceSummary();
        });
    </script>
    <script>
        function initMap(mapId, lat, lng, markerTitle) {
            if (lat && lng) {
                const map = L.map(mapId).setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                L.marker([lat, lng]).addTo(map)
                    .bindPopup(markerTitle)
                    .openPopup();
            } else {
                document.getElementById(mapId).innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 bg-light text-muted">Lokasi tidak tersedia</div>';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            initMap('pickupMap', {{ $order->pickup_latitude ?? 'null' }}, {{ $order->pickup_longitude ?? 'null' }}, 'Lokasi Penjemputan');
            initMap('destinationMap', {{ $order->destination_latitude ?? 'null' }}, {{ $order->destination_longitude ?? 'null' }}, 'Lokasi Tujuan');
        });
    </script>
@endpush
