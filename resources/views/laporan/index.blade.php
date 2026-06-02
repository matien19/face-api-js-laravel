@extends('layouts.main')

@section('content')
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-body-tertiary">Total Presensi</h6>
                <h2 class="mb-0">{{ $totalPresensi ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-success">Tepat Waktu</h6>
                <h2 class="mb-0">{{ $tepat ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-warning">Datang Awal</h6>
                <h2 class="mb-0">{{ $awal ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-danger">Terlambat</h6>
                <h2 class="mb-0">{{ $terlambat ?? 0 }}</h2>
            </div>
        </div>
    </div>

</div>

<div id="attendance" data-list='{"valueNames":["no","user","tanggal","jam","status"],"page":10,"pagination":true}'>

    <div class="mb-4">
        <div class="row g-3">

            <div class="col-auto">
                <input type="date" class="form-control">
            </div>

            <div class="col-auto">
                <select class="form-select">
                    <option value="">Semua Status</option>
                    <option value="awal">Awal</option>
                    <option value="tepat">Tepat Waktu</option>
                    <option value="terlambat">Terlambat</option>
                </select>
            </div>

            <div class="col-auto">
                <div class="search-box">
                    <form class="position-relative">
                        <input class="form-control search-input search" type="search" placeholder="Cari pengguna..."
                            aria-label="Search" />
                        <span class="fas fa-search search-box-icon"></span>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div
        class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis border-top border-bottom border-translucent position-relative top-1">

        <div class="table-responsive scrollbar-overlay mx-n1 px-1">

            <table class="table table-sm fs-9 mb-0">

                <thead>
                    <tr>
                        <th class="sort align-middle" data-sort="no" style="width:5%">
                            NO
                        </th>

                        <th class="sort align-middle" data-sort="user" style="width:25%">
                            PENGGUNA
                        </th>

                        <th class="sort align-middle" data-sort="tanggal" style="width:15%">
                            TANGGAL
                        </th>

                        <th class="sort align-middle" data-sort="jam" style="width:15%">
                            JAM MASUK
                        </th>

                        <th class="sort align-middle" data-sort="status" style="width:15%">
                            STATUS
                        </th>

                        <th class="align-middle" style="width:15%">
                            KETERANGAN
                        </th>

                        <th class="align-middle text-end" style="width:10%">
                            AKSI
                        </th>
                    </tr>
                </thead>

                <tbody class="list">

                    @foreach($presensis as $presensi)
                    <tr>

                        <td class="no align-middle">
                            {{ $loop->iteration }}
                        </td>

                        <td class="user align-middle">
                            <div class="d-flex align-items-center">

                                <div class="avatar avatar-m">
                                    <img class="rounded-circle"
                                        src="{{ $presensi->user->foto ?? asset('label/default.jpg') }}" alt="">
                                </div>

                                <div class="ms-3">
                                    <h6 class="mb-0 fw-semibold">
                                        {{ $presensi->user->name }}
                                    </h6>
                                    <small class="text-body-secondary">
                                        {{ $presensi->user->email }}
                                    </small>
                                </div>

                            </div>
                        </td>

                        <td class="tanggal align-middle">
                            {{ \Carbon\Carbon::parse($presensi->waktu_masuk)->translatedFormat('d F Y') }}
                        </td>

                        <td class="jam align-middle">
                            {{ \Carbon\Carbon::parse($presensi->waktu_masuk)->format('H:i:s') }}
                        </td>

                        <td class="status align-middle">

                            @if($presensi->status == 'awal')
                            <span class="badge badge-phoenix badge-phoenix-warning">
                                Datang Awal
                            </span>

                            @elseif($presensi->status == 'tepat')
                            <span class="badge badge-phoenix badge-phoenix-success">
                                Tepat Waktu
                            </span>

                            @else
                            <span class="badge badge-phoenix badge-phoenix-danger">
                                Terlambat
                            </span>
                            @endif

                        </td>

                        <td class="align-middle">

                            @if($presensi->status == 'awal')
                            Hadir sebelum jam kerja

                            @elseif($presensi->status == 'tepat')
                            Hadir sesuai jadwal

                            @else
                            Hadir melewati batas waktu
                            @endif

                        </td>

                        <td class="align-middle text-end">

                            <button class="btn btn-sm btn-phoenix-secondary">
                                <span class="fas fa-eye"></span>
                            </button>

                        </td>

                    </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="row align-items-center justify-content-between py-2 pe-0 fs-9">

            <div class="col-auto d-flex">
                <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p>

                <a class="fw-semibold" href="#!" data-list-view="*">
                    View all
                    <span class="fas fa-angle-right ms-1"></span>
                </a>

                <a class="fw-semibold d-none" href="#!" data-list-view="less">
                    View Less
                    <span class="fas fa-angle-right ms-1"></span>
                </a>
            </div>

            <div class="col-auto d-flex">

                <button class="page-link" data-list-pagination="prev">
                    <span class="fas fa-chevron-left"></span>
                </button>

                <ul class="mb-0 pagination"></ul>

                <button class="page-link pe-0" data-list-pagination="next">
                    <span class="fas fa-chevron-right"></span>
                </button>

            </div>

        </div>

    </div>
</div>
@endsection