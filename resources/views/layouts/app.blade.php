<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Intern PHP Source</title>

    <!-- Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link href="https://code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">

    <!-- CSS Files -->
    <link href="{{ asset('css/lib/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/lib/font-awesome/css/all.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet" />

    @vite(['resources/css/common.css'], 'build')
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <x-partials.header />
    <x-partials.menu />
    <div class="content-wrapper">
        @yield('breadcumb')
        <section class="content">
            <div class="container-fluid">
                @if (Session::has('error'))
                    @php
                        $errorType = 'danger';
                        $errorMessages = Session::get('error');
                    @endphp
                    <x-alert :messages="$errorMessages" :type="$errorType" />
                @endif
                @if (Session::has('success'))
                    @php
                        $successType = 'success';
                        $successMessages = Session::get('success');
                    @endphp
                    <x-alert :messages="$successMessages" :type="$successType" />
                @endif
                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger">
                        <ul class="m-0">
                            @php
                                $errorsUnique = array_unique($errors->all());
                            @endphp
                            @if (count($errorsUnique) === 1)
                                @foreach ($errorsUnique as $error)
                                    {!! $error !!}
                                @endforeach
                            @else
                                @foreach ($errorsUnique as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                @endif
                {{ $slot }}
            </div>
        </section>
    </div>
    <x-partials.footer />
    <x-loading />
    <!-- JS Files -->
    <script src="{{ asset('js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.0-rc.2/jquery-ui.min.js"
        integrity="sha256-RQLbEU539dpygNMsBGZlplus6CkitaLy0btTCHcULpI=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="{{ asset('js/lib/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-validation/jquery.validate.min.js') }}">
        < /scrip> <
        script src = "{{ asset('js/lib/jquery-validation/additional-methods.min.js') }}" >
    </script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    @vite(['resources/js/common.js', 'resources/js/lib/jquery-validation/additional-setting.js'], 'build')
    @vite(['resources/js/common.js', 'resources/js/lib/jquery-validation/my-validation.js'], 'build')
    @stack('scripts')
</body>

</html>
