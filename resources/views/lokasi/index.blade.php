@extends('layouts.main')
@section('content')
<div class="row g-2 mb-4">
    <div class="col-auto">
        <h2 class="mb-0">Lokasi</h2>
    </div>
</div>
<div id="products" data-list='{"valueNames":["no", "lokasi"],"page":10,"pagination":true}'>
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
                        <th class="sort align-middle pe-5" scope="col" data-sort="lokasi" style="width:10%;">LOKASI</th>
                        <th class="sort align-middle text-end" scope="col" style="width:15%;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="list" id="customers-table-body">
                    @foreach ($data as $item)
                    <tr>
                        <td class="no align-middle">{{ $loop->iteration }}</td>
                        <td class="lokasi align-middle">{{ $item->nama_lokasi }}</td>
                        <td class="align-middle text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="#" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
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
        <form action="{{ route('md.lokasi.tambah') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header justify-content-between bg-primary">
                    <h5 class="modal-title text-white" id="tambahLabel">
                        Tambah Lokasi
                    </h5>
                    <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fas fa-times fs-9 text-white"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" required>
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
    document.addEventListener('DOMContentLoaded', async function () {
        await Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models')
        ]);
    });
    
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