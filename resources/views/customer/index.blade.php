@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
    <div class="container mt-4">
        <div class="page-header mb-3">
            <h1 class="page-title">Data Customer</h1>
        </div>

        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter by Date --}}
        <div class="card mb-4 p-3 shadow-sm">
            <h5 class="mb-3">Filter by Date:</h5>
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-primary btn-sm">All Records</button>
                <button class="btn btn-outline-primary btn-sm">Today</button>
                <button class="btn btn-outline-primary btn-sm">Yesterday</button>
                <button class="btn btn-outline-primary btn-sm">This Week</button>
                <button class="btn btn-outline-primary btn-sm">Last Week</button>
                <button class="btn btn-outline-primary btn-sm">This Month</button>
                <button class="btn btn-outline-primary btn-sm">Last Month</button>
                <button class="btn btn-outline-primary btn-sm">Last 7 Days</button>
                <button class="btn btn-outline-primary btn-sm">Last 30 Days</button>
                <button class="btn btn-outline-dark btn-sm">Custom Date Range</button>
            </div>
        </div>

        <div class="mb-3 d-flex gap-2">
            <!-- Tombol Modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                + Tambah Lead Baru 
            </button>
        </div>


        {{-- Tabel Data Customer --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>Nomor Telepon</th>
                            <th>Email</th>
                            <th>Lokasi & Alamat</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Coverage</th>
                            <th>Product</th>
                            <th>Assign To</th>
                            <th>Submitted At</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $customer['name'] }}</td>
                                <td>{{ $customer['phone'] }}</td>
                                <td>{{ $customer['email'] }}</td>
                                <td>{{ $customer['address'] }}</td>
                                <td>{{ $customer['latitude'] }}</td>
                                <td>{{ $customer['longitude'] }}</td>
                                <td>{{ $customer['coverage'] }}</td>
                                <td>{{ $customer['product'] }}</td>
                                <td>{{ $customer['assign_to'] }}</td>
                                <td>{{ $customer['submitted_at'] }}</td>
                                <td>{{ $customer['submitted'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">Belum ada data customer</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Create Customer -->
    <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('customer.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCustomerLabel">Tambah Lead Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pelanggan</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Masukkan nama pelanggan" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control" placeholder="Contoh: 0812345678"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Masukkan alamat lengkap" required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kode Referral</label>
                                <input type="text" name="referral_code" class="form-control"
                                    placeholder="Masukkan kode referral (opsional)">
                            </div>
                        </div>

                        <h6 class="mt-3">Wilayah</h6>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Provinsi</label>
                                <select name="province" class="form-select">
                                    <option>Pilih Provinsi</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kota/Kabupaten</label>
                                <select name="city" class="form-select">
                                    <option>Pilih Kota/Kabupaten</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kecamatan</label>
                                <select name="district" class="form-select">
                                    <option>Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kelurahan/Desa</label>
                                <select name="village" class="form-select">
                                    <option>Pilih Kelurahan/Desa</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Divisi</label>
                                <select name="division" class="form-select" required>
                                    <option value="">Pilih Divisi</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="sales_retail">Sales Retail</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kategori Produk</label>
                                <select name="product_category" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="broadband_internet">Broadband Internet</option>
                                    <option value="business_solutions">Business Solutions</option>
                                    <option value="promo_jepara">Promo Spesial Jepara</option>
                                    <option value="promo_sukoharjo">Promo Spesial Sukoharjo</option>
                                    <option value="sudirman_park">Sudirman Park</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Coverage</label>
                                <select name="coverage" class="form-select" required>
                                    <option value="">Pilih Coverage</option>
                                    <option value="cover">Cover</option>
                                    <option value="uncover">Uncover</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
