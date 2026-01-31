<!doctype html>
<html lang="en">

<head>
    <title>{{ $title ?? 'Melting Material Control' }}</title>
    @include('components.head')

    {{-- Customize styles per page --}}
    @stack('style')
</head>

<body data-topbar="dark" data-layout="horizontal">

    <!-- Begin page -->
    <div id="layout-wrapper">

        @livewire('components.topbar')

        <!-- ========== Left Sidebar Start ========== -->
        @livewire('components.top_nav')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->

        <!-- main content -->
        <div class="main-content">

            {{ $slot }}

            @include('components.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('components.rightbar')
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    @include('components.scripts')

    <script>
        // Custom scripts can be added here
        document.addEventListener("DOMContentLoaded", function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: 5000, // 5000 ms = 5 detik
                extendedTimeOut: 1000 // tambahan jika hover
            };
            // Your custom JavaScript code here
            @if (session()->has('message'))
                toastr.success("{{ session('message') }}");
            @endif

            @if (session()->has('error'))
                toastr.error("{{ session('error') }}");
            @endif
            Livewire.on('success', (e) => {
                toastr.success(e.message);
            })
            Livewire.on('error', (e) => {
                toastr.error(e.message);
            })
        });
    </script>

    {{-- Customize scripts per page --}}
    @stack('scripts')
</body>

</html>