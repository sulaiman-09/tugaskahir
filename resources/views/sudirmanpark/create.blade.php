@extends('layouts.app')

@section('title', 'Tambah Customer Sudirman Park')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Add New Customer</h5>
            </div>

            {{-- Body --}}
            <div class="card-body bg-light-subtle p-4">
                <form action="{{ route('sudirmanpark.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            {{-- Customer Name --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Customer Name</label>
                                <input type="text" name="name"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white" required>
                            </div>

                            {{-- Phone Number --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Phone Number</label>
                                <input type="text" name="phone"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white" required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Email</label>
                                <input type="email" name="email"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white">
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Status</label>
                                <select name="status" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                                    <option value="registration">Registration</option>
                                    <option value="processed">Processed</option>
                                    <option value="approved">Approved</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            {{-- Package --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Package</label>
                                <select name="package" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                                    <option value="">Select Package</option>
                                    @if (isset($products))
                                        @foreach ($products as $p)
                                            <option
                                                value="{{ $p->name }} - Rp {{ number_format($p->price, 0, ',', '.') }}">
                                                {{ $p->name }} - Rp {{ number_format($p->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Tower Address --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Tower Address</label>
                                <select name="tower" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                                    <option value="">Select Tower Address</option>
                                    @if (isset($addresses))
                                        @foreach ($addresses as $id => $full_address)
                                            @php
                                                $display = preg_replace('/GF/', '01', $full_address);
                                                $display = preg_replace('/(\d+)$/', '', $display);
                                                $display = trim($display, '- ');
                                            @endphp
                                            <option value="{{ $display }}">{{ $display }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- ID Card Photo --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">ID Card Photo</label>
                                <input type="file" name="ktp"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white" accept="image/*,.pdf">
                            </div>

                            {{-- Payment Proof --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Payment Proof</label>
                                <input type="file" name="payment_proof"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white" accept="image/*,.pdf">
                            </div>

                            {{-- Notes --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Notes</label>
                                <textarea name="note" class="form-control rounded-3 shadow-sm border-0 bg-white" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                        <a href="{{ route('sudirmanpark.index') }}"
                            class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                            <i class="bi bi-save2 me-1"></i> Create Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Style tambahan agar mirip form User --}}
    <style>
        body {
            background-color: #f8fafc !important;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: #aacbff !important;
            box-shadow: 0 0 5px rgba(99, 162, 255, 0.35) !important;
        }

        .btn-primary {
            background-color: #0d6efd !important;
            border: none !important;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7 !important;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f3f5 !important;
        }
    </style>
@endsection
