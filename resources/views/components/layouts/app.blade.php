<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
    <!-- <script src="https://kit.fontawesome.com/YOUR_KIT_URL.js" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css?v=' . filemtime(public_path('css/app.css'))) }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/phosphor-icons"></script>
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
</head>

@guest
<livewire:hrlogin/>
@else
<body>
    <div class="container-fluid " style="width: 100%; display: flex;flex-direction: column; height:100vh; ">
        <div class="row">
        <livewire:header />
        </div>
        <div class="row" style="display: flex; flex: 1 1 auto; overflow: hidden;">
            <div class="column1">
                <livewire:sidebar />
            </div>
            <div class="column2" >
                 <div>{{ $slot }}</div>
            </div>
        </div>
    </div>
    @livewireScripts
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
</body>

@endguest
</html>
