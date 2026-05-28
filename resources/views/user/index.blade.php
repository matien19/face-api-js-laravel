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
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambah">
                    <span class="fas fa-plus me-2"></span> Tambah Data
                </button>
            </div>
            <div class="col-auto">
                <div class="search-box">
                    <form class="position-relative"><input class="form-control search-input search" type="search"
                            placeholder="Search customers" aria-label="Search" />
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
                        <th class="sort align-middle pe-5" scope="col" data-sort="no" style="width:5%;">NO</th>
                        <th class="sort align-middle pe-5" scope="col" data-sort="customer" style="width:10%;">PENGGUNA
                        </th>
                        <th class="sort align-middle pe-5" scope="col" data-sort="email" style="width:20%;">EMAIL</th>
                        <th class="sort align-middle ps-7" scope="col" data-sort="city" style="width:25%;">ALAMAT</th>
                        <th class="sort align-middle text-end" scope="col" data-sort="last-seen" style="width:15%;">LAST
                            SEEN</th>
                        <th class="sort align-middle text-end" scope="col" style="width:15%;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="list" id="customers-table-body">
                    @foreach ($data as $user)
                    <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                        <td class="no align-middle white-space-nowrap pe-5">{{ $loop->iteration }}</td>
                        <td class="customer align-middle white-space-nowrap pe-5"><a
                                class="d-flex align-items-center text-body-emphasis" href="">
                                <div class="avatar avatar-m"><img class="rounded-circle"
                                        src="{{ $user->foto ?? asset('label/default.jpg') }}" alt="" /></div>
                                <p class="mb-0 ms-3 text-body-emphasis fw-bold">{{ $user->name }}</p>
                            </a>
                        </td>
                        <td class="email align-middle white-space-nowrap pe-5"><a class="fw-semibold"
                                href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td class="city align-middle white-space-nowrap text-body-highlight ps-7">{{ $user->alamat ??
                            '-' }}
                        </td>
                        <td class="last-seen align-middle white-space-nowrap text-body-tertiary text-end">34 min ago
                        </td>
                        <td class="action align-middle white-space-nowrap text-end">

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
<div class="modal fade" id="tambah" tabindex="-1" data-bs-backdrop="static" aria-labelledby="tambah" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('md.user.tambah') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header justify-content-between bg-primary">
                    <h5 class="modal-title text-white" id="tambahLabel">
                        Tambah User
                    </h5>

                    <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fas fa-times fs-9 text-white"></span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    {{-- <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div> --}}
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="hidden" name="descriptor" id="descriptor">
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                        <div class="mt-3 text-center">
                            <img id="preview-foto" src="{{ asset('label/default.jpg') }}" class="img-thumbnail d-none"
                                style="max-height: 200px;">
                            <p id="preview-status" class="mt-2 text-info"></p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">
                        Simpan
                    </button>

                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script defer src="{{ asset('js/face-api.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        autoShowSessionAlert({
            success: "{{ session('success') }}",
            error: "{{ session('error') }}",
            warning: "{{ session('warning') }}",
            info: "{{ session('info') }}"
        });
    });
</script>
<script>
    function setFotoLoading(loading) {
    const controls = document.querySelectorAll('#tambah form input, #tambah form textarea, #tambah form button[type="submit"]');
    controls.forEach(el => el.disabled = loading);
    const status = document.getElementById('preview-status');
    status.textContent = loading ? 'Mendeteksi wajah, harap tunggu...' : '';
}

document.getElementById('foto')
    .addEventListener('change', async function (event) {
        const file = event.target.files[0];
        if (!file) return;
        setFotoLoading(true);
        const preview = document.getElementById('preview-foto');
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');

        try {
            const img = await faceapi.bufferToImage(file);
            const detection = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();

            if (!detection) {
                showAlert('error', 'Wajah tidak terdeteksi, ganti foto dengan yang lebih jelas');
                document.getElementById('descriptor').value = '';
                return;
            }

            document.getElementById('descriptor').value = JSON.stringify(Array.from(detection.descriptor));
            showAlert('success', 'Wajah berhasil dideteksi');
        } catch (error) {
            showAlert('error', 'Terjadi kesalahan saat mendeteksi wajah');
            document.getElementById('descriptor').value = '';
        } finally {
            setFotoLoading(false);
        }
    });

</script>
@endpush