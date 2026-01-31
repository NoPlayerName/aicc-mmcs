{{-- @livewireScripts --}}
<!-- JAVASCRIPT -->


<script src={{ asset('assets/libs/jquery/jquery.min.js') }}></script>
<script src={{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}></script>
<script src={{ asset('assets/libs/metismenu/metisMenu.min.js') }}></script>
<script src={{ asset('assets/libs/simplebar/simplebar.min.js') }}></script>
<script src={{ asset('assets/libs/node-waves/waves.min.js') }}></script>
<script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Sweet alert init js-->
<script src="{{ asset('assets/js/sweet.min.js') }}"></script>
<script src="{{ asset('assets/js/general.js') }}"></script>

<!-- date picker -->
<script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

<!-- jquery.vectormap map -->
<script src={{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}></script>
<script src={{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}></script>

<!-- select2 -->
<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

<!-- Required datatable js -->
<script src={{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}></script>
<script src={{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}></script>

<!-- Responsive examples -->
<script src={{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}></script>
<script src={{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}></script>

{{-- <script src={{ asset('assets/js/pages/dashboard.init.js') }}></script> --}}

{{-- datatable init js --}}
{{-- <script src={{ asset('assets/js/pages/datatables.init.js') }}></script> --}}

<script src={{ asset('assets/libs/toastr/build/toastr.min.js') }}></script>

<script src={{ asset('assets/js/app.js') }}></script>

<script src="{{ asset('vendor/livewire/livewire.js') }}" data-update-uri="{{ url('/livewire/update') }}"
    data-csrf="{{ csrf_token() }}" data-navigate-once="true">
</script>