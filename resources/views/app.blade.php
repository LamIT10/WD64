<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    {{-- @vite('resources/js/app.js') --}}
    @vite('resources/css/app.css')
    {{-- @routes
    @inertiaHead --}}
    <script src="https://kit.fontawesome.com/84084c404d.js" crossorigin="anonymous"></script>
  </head>
  <body class="bg-[#F8F9FA] flex flex-wrap">
    <nav class="w-[20%] h-[938px] bg-[#FCFCFD]">
      @include('layouts.partials.side_bar')
    </nav>
    <div class="w-[80%]">
    <header class="w-full">
      @include('layouts.partials.header')
    </header>
    {{-- CONTENT --}}
    @yield('content')
  </div>
    {{-- @inertia --}}


 
  </body>
</html>
