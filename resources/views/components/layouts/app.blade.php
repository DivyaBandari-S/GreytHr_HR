<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Hr Xpert' }}</title>


    <!-- Load jQuery First -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Load Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css?v=' . filemtime(public_path('css/app.css'))) }}">
    <link rel="stylesheet" href="{{ asset('css/employee.css?v=' . filemtime(public_path('css/employee.css'))) }}">

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Flatpickr (Date Picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/phosphor-icons"></script>

    <!-- Load Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.2/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    @livewireStyles
</head>

<body>
    <script src="{{ asset('js/admin-dash.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-toggled');
        }
    </script>
    @guest
        {{ $slot }}
    @else
        <section id="main">
            @livewire('main-layout')
            <div class="main__body " style="overflow: auto; height: calc(100vh - 84px);background-color: #f3f3f3;">
                {{ $slot }} </div>
        </section>
    @endguest
    @livewireScripts
</body>

</html>
