@extends('layouts.app')

@section('title', 'Data Pelanggan')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* small helper to keep layout tidy inside existing app */
        .card-rounded { border-radius: 12px; }
    </style>
@endpush

@section('content')
    <div x-data="customerDashboard(@json($customers->items()))" x-init="initDatepickers()" class="p-6">
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Data Pelanggan</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('customer.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#FF8A7A] to-[#F86F5C] text-white px-4 py-2 rounded-lg shadow">+ Tambah Pelanggan</a>
                    <a href="{{ route('customer.export', request()->query()) }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-3 py-2 rounded-lg">Export</a>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-4 text-green-700 bg-green-50 p-3 rounded">{{ session('success') }}</div>
            @endif

            {{-- Filters / Search --}}
            <div class="mb-4 space-y-4">
                <div class="flex flex-wrap items-center gap-4">
                    <form action="{{ route('customer.index') }}" method="GET" class="flex-1 min-w-[250px] relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="pl-10 pr-4 py-2 border rounded-lg w-full" placeholder="Cari nama, email, atau telepon...">
                    </form>
                    <div class="flex-1 min-w-[300px]">
                        <input id="date-range-picker" name="date_range" class="border rounded-lg py-2 px-3 w-full" type="text" placeholder="Pilih rentang tanggal">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" @click="setActiveDateFilter('today')" :class="{'bg-[#F86F5C] text-white': activeDateFilter==='today','bg-gray-200 text-gray-700': activeDateFilter!=='today'}" class="px-3 py-1 text-sm rounded-full">Hari Ini</button>
                    <button type="button" @click="setActiveDateFilter('yesterday')" :class="{'bg-[#F86F5C] text-white': activeDateFilter==='yesterday','bg-gray-200 text-gray-700': activeDateFilter!=='yesterday'}" class="px-3 py-1 text-sm rounded-full">Kemarin</button>
                    <button type="button" @click="setActiveDateFilter('week')" :class="{'bg-[#F86F5C] text-white': activeDateFilter==='week','bg-gray-200 text-gray-700': activeDateFilter!=='week'}" class="px-3 py-1 text-sm rounded-full">Minggu Ini</button>
                    <button type="button" @click="setActiveDateFilter('month')" :class="{'bg-[#F86F5C] text-white': activeDateFilter==='month','bg-gray-200 text-gray-700': activeDateFilter!=='month'}" class="px-3 py-1 text-sm rounded-full">Bulan Ini</button>
                </div>
            </div>

            {{-- Actions toolbar --}}
            <div class="flex items-center justify-between mb-4">
                <div x-show="selectedRows.length>0" class="flex items-center gap-4" x-cloak>
                    <div class="text-sm text-gray-600" x-text="selectedRows.length + ' baris terpilih'"></div>
                    <button @click="openDeleteModal(selectedRows)" class="text-red-600 px-3 py-1 rounded">Hapus</button>
                </div>

                <div class="flex items-center gap-3">
                    <div x-data="{open:false}">
                        <button @click="open = !open" class="px-3 py-1 border rounded">Kolom</button>
                        <div x-show="open" @click.away="open=false" class="absolute mt-2 bg-white p-2 rounded shadow" x-cloak>
                            <template x-for="(visible, column) in columns" :key="column">
                                <label class="flex items-center gap-2 p-1"><input type="checkbox" x-model="columns[column]"><span x-text="columnLabels[column]"></span></label>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3"><input type="checkbox" @change="toggleSelectAll($event)" class="h-4 w-4"></th>
                            <template x-for="column in Object.keys(columns)" :key="column"><th x-show="columns[column]" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase" x-text="columnLabels[column]"></th></template>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                            @php
                                $parts = preg_split('/\s+/', trim($customer->name));
                                $initials = strtoupper(substr($parts[0] ?? '',0,1) . (isset($parts[1]) ? substr($parts[1],0,1): ''));
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4"><input type="checkbox" :value="{{ $customer->id }}" x-model="selectedRows" class="h-4 w-4"></td>
                                <td x-show="columns.name" class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-[#7C3AED] flex items-center justify-center text-white font-bold">{{ $initials }}</div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td x-show="columns.phone" class="px-6 py-4 text-sm text-gray-700">{{ $customer->phone }}</td>
                                <td x-show="columns.location" class="px-6 py-4"><div class="text-sm text-gray-900">{{ $customer->address }}</div><div class="text-sm text-gray-500">{{ $customer->province ?? '' }} {{ $customer->city ?? '' }}</div></td>
                                <td x-show="columns.product" class="px-6 py-4 text-sm text-gray-700">{{ $customer->product }}</td>
                                <td x-show="columns.coverage" class="px-6 py-4 text-sm text-gray-700">{{ $customer->coverage }}</td>
                                <td x-show="columns.status" class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs" :class="{{ json_encode($customer->submitted ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">{{ $customer->submitted ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                                <td x-show="columns.latitude" class="px-6 py-4 text-sm text-gray-700">{{ $customer->latitude }}</td>
                                <td x-show="columns.longitude" class="px-6 py-4 text-sm text-gray-700">{{ $customer->longitude }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('customer.edit', $customer->id) }}" class="text-gray-500 hover:text-indigo-600" title="Edit"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                                        <form action="{{ route('customer.destroy', $customer->id) }}" method="POST" class="delete-form" data-name="{{ $customer->name }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-500 hover:text-red-600"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination and summary --}}
            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-gray-600">Menampilkan <span class="font-medium">{{ $customers->firstItem() ?? 0 }}</span>-<span class="font-medium">{{ $customers->lastItem() ?? 0 }}</span> dari <span class="font-medium">{{ $customers->total() }}</span> hasil</div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div @click.away="isModalOpen=false" class="bg-white rounded-xl shadow-2xl w-full max-w-3xl mx-4">
                <div class="flex items-center justify-between p-5 border-b"><h3 class="text-xl font-bold" x-text="modalTitle"></h3><button @click="isModalOpen=false" class="text-gray-400">✕</button></div>
                <div class="p-6"><!-- form fields could be placed here if needed --></div>
                <div class="flex items-center justify-end p-5 space-x-2 border-t"><button @click="isModalOpen=false" class="px-4 py-2 border rounded">Batal</button><button @click="saveCustomer()" class="px-4 py-2 bg-[#F86F5C] text-white rounded">Simpan Data</button></div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="isDeleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div @click.away="isDeleteModalOpen=false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6 text-center">
                <div class="mb-4 text-lg font-medium">Anda yakin ingin menghapus data ini?</div>
                <div class="mb-4 text-sm text-gray-500" x-text="`Anda akan menghapus ${idsToDelete.length} data pelanggan.`"></div>
                <div class="flex justify-center gap-2"><button @click="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded">Ya, saya yakin</button><button @click="isDeleteModalOpen=false" class="px-4 py-2 border rounded">Batalkan</button></div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function customerDashboard(initialCustomers) {
            return {
                customers: initialCustomers || [],
                columns: { name: true, phone: true, location: true, product: true, coverage: true, status: true, latitude: false, longitude: false },
                columnLabels: { name: 'Nama Pelanggan', phone: 'Telepon', location: 'Lokasi', product: 'Produk', coverage: 'Coverage', status: 'Status', latitude: 'Latitude', longitude: 'Longitude' },
                selectedRows: [],
                isModalOpen: false,
                isDeleteModalOpen: false,
                modalTitle: '',
                idsToDelete: [],
                activeDateFilter: 'month',
                toast: { show: false, message: '' },
                initDatepickers() { flatpickr('#date-range-picker', { mode: 'range', dateFormat: 'd M Y' }); },
                setActiveDateFilter(f){ this.activeDateFilter = f; },
                toggleSelectAll(e){ this.selectedRows = e.target.checked ? this.customers.map(c=>c.id) : []; },
                openAddModal(){ this.modalTitle='Tambah Pelanggan Baru'; this.isModalOpen=true; },
                openEditModal(c){ this.modalTitle='Edit Pelanggan'; this.isModalOpen=true; },
                openDeleteModal(ids){ this.idsToDelete = ids; this.isDeleteModalOpen = true; },
                saveCustomer(){ this.isModalOpen=false; this.showToast('Data pelanggan berhasil disimpan!'); },
                showToast(m){ this.toast.message = m; this.toast.show=true; setTimeout(()=>this.toast.show=false,3000); },
                confirmDelete(){
                    // submit delete forms for each id
                    this.idsToDelete.forEach(id=>{
                        const form = document.querySelector(`form[action$="/${id}"]`);
                        if(form) form.submit();
                    });
                }
            }
        }

        // delete confirmation for non-js fallback
        document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('.delete-form').forEach(function(form){
                form.addEventListener('submit', function(e){
                    if(!confirm('Hapus ' + (form.dataset.name || 'data ini') + '? Aksi ini tidak dapat dibatalkan.')) e.preventDefault();
                });
            });
        });
    </script>
@endpush
