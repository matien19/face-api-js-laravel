@extends('layouts.main')
@section('content')
<div class="row g-2 mb-4">
    <div class="col-auto">
        <h2 class="mb-0">Pengguna</h2>
    </div>
</div>
<div id="products" data-list='{"valueNames":["no", "customer","email","city","last-seen"],"page":10,"pagination":true}'>
    <div class="mb-4">
        <div class="row g-3">
            <div class="col-auto">
                <div class="search-box">
                    <form class="position-relative"><input class="form-control search-input search" type="search"
                            placeholder="Search customers" aria-label="Search" />
                        <span class="fas fa-search search-box-icon"></span>
                    </form>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-link text-body me-4 px-0">
                    <span class="fa-solid fa-file-export fs-9 me-2"></span> Export
                </button>
                <button class="btn btn-primary">
                    <span class="fas fa-plus me-2"></span> Tambah Data
                </button>
            </div>
        </div>
    </div>
    <div
        class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis border-top border-bottom border-translucent position-relative top-1">
        <div class="table-responsive scrollbar-overlay mx-n1 px-1">
            <table class="table table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th class="sort align-middle pe-5" scope="col" data-sort="no" style="width:5%;">NO</th>
                        <th class="sort align-middle pe-5" scope="col" data-sort="customer" style="width:10%;">PENGGUNA
                        </th>
                        <th class="sort align-middle pe-5" scope="col" data-sort="email" style="width:20%;">EMAIL</th>
                        <th class="sort align-middle ps-7" scope="col" data-sort="city" style="width:25%;">ALAMAT</th>
                        <th class="sort align-middle text-end" scope="col" data-sort="last-seen" style="width:15%;">LAST
                            SEEN</th>
                    </tr>
                </thead>
                <tbody class="list" id="customers-table-body">
                    @foreach ($data as $user)
                    <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                        <td class="no align-middle white-space-nowrap pe-5">{{ $loop->iteration }}</td>
                        <td class="customer align-middle white-space-nowrap pe-5"><a
                                class="d-flex align-items-center text-body-emphasis" href="customer-details.html">
                                <div class="avatar avatar-m"><img class="rounded-circle"
                                        src="{{ $user->foto ?? asset('storage/foto/default.jpg') }}" alt="" /></div>
                                <p class="mb-0 ms-3 text-body-emphasis fw-bold">{{ $user->name }}</p>
                            </a></td>
                        <td class="email align-middle white-space-nowrap pe-5"><a class="fw-semibold"
                                href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td class="city align-middle white-space-nowrap text-body-highlight ps-7">{{ $user->alamat ??
                            '-' }}</td>
                        <td class="last-seen align-middle white-space-nowrap text-body-tertiary text-end">34 min ago
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row align-items-center justify-content-between py-2 pe-0 fs-9">
            <div class="col-auto d-flex">
                <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p><a
                    class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1"
                        data-fa-transform="down-1"></span></a><a class="fw-semibold d-none" href="#!"
                    data-list-view="less">View Less<span class="fas fa-angle-right ms-1"
                        data-fa-transform="down-1"></span></a>
            </div>
            <div class="col-auto d-flex"><button class="page-link" data-list-pagination="prev"><span
                        class="fas fa-chevron-left"></span></button>
                <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span
                        class="fas fa-chevron-right"></span></button>
            </div>
        </div>
    </div>
</div>
@endsection