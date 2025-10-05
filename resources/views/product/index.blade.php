@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-3 fw-bold">Product Management</h3>

    <!-- Tombol Aksi -->
    <div class="mb-3">
        <a href="#" class="btn btn-primary">+ Tambah Product Baru</a>
    </div>

    <!-- ======================= -->
    <!-- TABEL 1 : Category & Benefit -->
    <!-- ======================= -->
    <div class="card mb-4">
        <div class="card-body">
            <!-- Header & Pencarian -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Category and Benefit</h6>

                <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary ms-2">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Tabel Produk -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Category Name</th>
                            <th>Slug</th>
                            <th>Short Description</th>
                            <th>Show Price</th>
                            <th>Benefits</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Broadband Internet</td>
                            <td>broadband-internet</td>
                            <td>Internet cepat dan stabil untuk rumah tangga dan bisnis kecil</td>
                            <td>
                                <span class="badge bg-success">Shown</span>
                            </td>
                            <td class="text-start">
                                <ul class="mb-0">
                                    <li>📺 Bonus puluhan channel TV</li>
                                    <li>📶 Koneksi stabil untuk aktivitas online</li>
                                    <li>💰 Harga terjangkau untuk rumah tangga</li>
                                    <li>∞ Internet tanpa batasan kuota</li>
                                </ul>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Business Solutions</td>
                            <td>business-solutions</td>
                            <td>Solusi komprehensif untuk konektivitas dan entertainment bisnis</td>
                            <td>
                                <span class="badge bg-secondary">Hidden</span>
                            </td>
                            <td class="text-start">
                                <ul class="mb-0">
                                    <li>⚡ Prioritas layanan pelanggan</li>
                                    <li>📊 Memenuhi kebutuhan bisnis modern</li>
                                    <li>🌐 IP statis untuk server dan hosting</li>
                                    <li>📡 SLA jaminan kecepatan & uptime</li>
                                </ul>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Promo Spesial Jepara</td>
                            <td>promo-spesial-jepara</td>
                            <td>Nikmati internet cepat dan stabil dengan harga spesial khusus Jepara</td>
                            <td>
                                <span class="badge bg-success">Shown</span>
                            </td>
                            <td class="text-start">
                                <ul class="mb-0">
                                    <li>🏷 Promo khusus hanya untuk area Jepara</li>
                                    <li>📶 Koneksi stabil untuk aktivitas harian</li>
                                    <li>💰 Harga bersahabat untuk keluarga</li>
                                </ul>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ======================= -->
    <!-- TABEL 2 : PRODUCT LIST -->
    <!-- ======================= -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Product List</h6>

                <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search product..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary ms-2">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Speed</th>
                            <th>Website Image</th>
                            <th>Apps Image</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2</td>
                            <td>Izzi Life 30</td>
                            <td>30 Mbps</td>
                            <td><img src="{{ asset('images/website1.jpg') }}" alt="Website Image" class="img-thumbnail" width="150"></td>
                            <td><img src="{{ asset('images/app1.jpg') }}" alt="App Image" class="img-thumbnail" width="150"></td>
                            <td>Broadband Internet</td>
                            <td>Rp 166.500</td>
                            <td>16/12/2024 14:56:36</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Dedicated Internet</td>
                            <td>10 Mbps</td>
                            <td><img src="{{ asset('images/website2.jpg') }}" alt="Website Image" class="img-thumbnail" width="150"></td>
                            <td><img src="{{ asset('images/app2.jpg') }}" alt="App Image" class="img-thumbnail" width="150"></td>
                            <td>Business Solutions</td>
                            <td>Rp 1.700.000</td>
                            <td>20/12/2024 10:31:37</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Izzi Life 100</td>
                            <td>100 Mbps</td>
                            <td><img src="{{ asset('images/website3.jpg') }}" alt="Website Image" class="img-thumbnail" width="150"></td>
                            <td><img src="{{ asset('images/app3.jpg') }}" alt="App Image" class="img-thumbnail" width="150"></td>
                            <td>Broadband Internet</td>
                            <td>Rp 388.500</td>
                            <td>26/12/2024 09:06:18</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
