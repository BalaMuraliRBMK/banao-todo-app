<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.header')
</head>

<body>
    @include('layouts.navbar')
    <div class="mt-6">
        @yield('content')
    </div>
</body>
@include('layouts.footer')

</html>
