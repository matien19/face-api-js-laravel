<script src="{{ asset("template/vendors/popper/popper.min.js")}}"></script>
<script src="{{ asset("template/vendors/bootstrap/bootstrap.min.js")}}"></script>
<script src="{{ asset("template/vendors/anchorjs/anchor.min.js")}}"></script>
<script src="{{ asset("template/vendors/is/is.min.js")}}"></script>
<script src="{{ asset("template/vendors/fontawesome/all.min.js")}}"></script>
<script src="{{ asset("template/vendors/lodash/lodash.min.js")}}"></script>
<script src="{{ asset("template/vendors/list.js/list.min.js")}}"></script>
<script src="{{ asset("template/vendors/feather-icons/feather.min.js")}}"></script>
<script src="{{ asset("template/vendors/dayjs/dayjs.min.js")}}"></script>
<script src="{{ asset("template/assets/js/phoenix.js")}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    function showAlert(type, message) {
        if (!message) return;

        let title = 'Informasi';

        if (type === 'success') title = 'Berhasil!';
        if (type === 'error') title = 'Oops!';
        if (type === 'warning') title = 'Peringatan!';
        if (type === 'info') title = 'Informasi';

        Swal.fire({
            title: title,
            text: message,
            icon: type
        });

    }

    function autoShowSessionAlert(sessionData) {
        if (!sessionData) return;
        Object.entries(sessionData).forEach(([type, msg]) => {
            if (msg) {
                showAlert(type, msg);
            }
        });
    }
    
</script>