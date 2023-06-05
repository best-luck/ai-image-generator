@stack('top_scripts')
<script src="{{ asset('assets/vendor/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/aos/aos.min.js') }}"></script>
@stack('scripts_libs')
<script src="{{ asset('assets/extra/js/extra.js') }}"></script>
@stack('scripts')
@toastr_render
