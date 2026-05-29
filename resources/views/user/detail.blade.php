@extends('layouts.main')
@section('content')
<div class="mb-9">
    <div class="row align-items-center justify-content-between g-3 mb-4">
        <div class="col-auto">
            <h2 class="mb-0">Detail Pengguna</h2>
        </div>
        <div class="col-auto">
            <div class="row g-3">
                <div class="col-auto"><button class="btn btn-phoenix-danger"><span
                            class="fa-solid fa-trash-can me-2"></span>Delete customer</button></div>
                <div class="col-auto"><button class="btn btn-phoenix-secondary"><span
                            class="fas fa-key me-2"></span>Reset password</button></div>
            </div>
        </div>
    </div>
    <div class="row g-5">
        <div class="col-12 col-xxl-4">
            <div class="row g-3 h-100">
                <div class="col-12 col-md-7 col-xxl-12">
                    <div class="card h-100 h-xxl-auto">
                        <div class="card-body d-flex flex-column justify-content-between pb-3">
                            <div class="row align-items-center g-5 mb-3 text-center text-sm-start">
                                <div class="col-12 col-sm-auto mb-sm-2">
                                    <div class="avatar avatar-5xl">
                                        <img class="rounded-circle"
                                            src="{{ $user->foto ? asset($user->foto) : asset('label/default.jpg') }}"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-auto flex-1">
                                    <h3>{{ $user->name ?? '-'}}</h3>
                                    {{-- <p class="text-body-secondary">Joined 3 months ago</p> --}}
                                    <div><a class="me-2" href="#!"><span
                                                class="fab fa-linkedin-in text-body-quaternary text-opacity-75 text-primary-hover"></span></a><a
                                            class="me-2" href="#!"><span
                                                class="fab fa-facebook text-body-quaternary text-opacity-75 text-primary-hover"></span></a><a
                                            href="#!"><span
                                                class="fab fa-twitter text-body-quaternary text-opacity-75 text-primary-hover"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-between-center border-top border-dashed pt-4">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5 col-xxl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <h3 class="me-1">Default Address</h3><button class="btn btn-link p-0"><span
                                        class="fas fa-pen fs-8 ms-3 text-body-quaternary"></span></button>
                            </div>
                            <h5 class="text-body-secondary">Address</h5>
                            <p class="text-body-secondary">
                                {{ $user->alamat ?? '-' }}
                            </p>
                            <div class="mb-3">
                                <h5 class="text-body-secondary">Email</h5><a href="mailto:{{ $user->email ?? '#' }}">
                                    {{ $user->email ?? '-' }}
                                </a>
                            </div>
                            <h5 class="text-body-secondary">Phone</h5><a class="text-body-secondary"
                                href="tel:{{ $user->phone ?? '#' }}">
                                {{ $user->phone ?? '-' }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@push('scripts')
@endpush