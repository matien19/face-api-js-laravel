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
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const toggleBtn = document.querySelector('.navbar-toggler');
        const sidebar = document.querySelector('.navbar-vertical');

        toggleBtn?.addEventListener('click', function () {

            sidebar.classList.toggle('navbar-narrow');

            // Optional untuk content
            document.body.classList.toggle('sidebar-narrow');

        });

    });
</script>