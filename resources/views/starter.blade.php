<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Sistem Pengajuan Akademik Maranatha')</title>
</head>

<body x-data="{
    page: 'ecommerce',
    loaded: true,
    darkMode: false,
    stickyMenu: false,
    sidebarToggle: false,
    scrollTop: false
  }" x-init="
    darkMode = JSON.parse(localStorage.getItem('darkMode')) ?? false;
    $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));
  " :class="{ 'dark bg-gray-900': darkMode }">

  <!-- ===== Preloader Start ===== -->
  @include('partials.preloader')
  <!-- ===== Preloader End ===== -->

  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <!-- ===== Sidebar Start ===== -->
    @include('layouts.sidebar')
    <!-- ===== Sidebar End ===== -->

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
      <!-- Small Device Overlay Start -->
      @include('partials.overlay')
      <!-- Small Device Overlay End -->

      <!-- ===== Header Start ===== -->
      @include('layouts.header')
      <!-- ===== Header End ===== -->

      <!-- ===== Main Content Start ===== -->
      <main>
        @if (session('success'))
            <x-notification type="success" title="Berhasil" :message="session('success')" />
        @endif

        @if (session('error'))
            <x-notification type="error" title="Gagal" :message="session('error')" />
        @endif

        @if (session('warning'))
            <x-notification type="warning" title="Peringatan" :message="session('warning')" />
        @endif

        @if (session('info'))
            <x-notification type="info" title="Informasi" :message="session('info')" />
        @endif
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

          @yield('content')
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <!-- ===== Page Wrapper End ===== -->
<script defer src="{{ asset('https://cdn.jsdelivr.net/npm/alpinejs@3.15.8/dist/cdn.min.js') }}"></script>
</body>

</html>
