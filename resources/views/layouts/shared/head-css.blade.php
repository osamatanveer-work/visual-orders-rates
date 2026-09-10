{{-- @yield('css') --}}
<!-- icons -->
{{-- <link href="{{Vite::asset('resources/css/icons.min.css')}}" rel="stylesheet" type="text/css" /> --}}
{{-- @vite(['assets/css/icons.min.css']) --}}
@if (isset($demo) && $demo == 'creative')
    @vite(['resources/scss/config/creative/app.scss', 'resources/scss/config/creative/bootstrap.scss'])
    {{-- <link href="{{Vite::asset('resources/css/config/creative/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/creative/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/creative/bootstrap-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    <link href="{{Vite::asset('resources/css/config/creative/app-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled /> --}}
@elseif(isset($demo) && $demo == 'modern')
    @vite(['resources/scss/config/modern/app.scss', 'resources/scss/config/modern/bootstrap.scss'])
    {{-- <link href="{{Vite::asset('resources/css/config/modern/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/modern/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/modern/bootstrap-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    <link href="{{Vite::asset('resources/css/config/modern/app-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled /> --}}
@elseif (isset($demo) && $demo == 'material')
    @vite(['resources/scss/config/material/app.scss', 'resources/scss/config/material/bootstrap.scss'])
    {{-- <link href="{{Vite::asset('resources/css/config/material/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/material/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/material/bootstrap-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    <link href="{{Vite::asset('resources/css/config/material/app-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled /> --}}
@elseif (isset($demo) && $demo == 'purple')
    @vite(['resources/scss/config/purple/app.scss', 'resources/scss/config/purple/bootstrap.scss'])
    {{-- <link href="{{Vite::asset('resources/css/config/purple/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/purple/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/purple/bootstrap-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    <link href="{{Vite::asset('resources/css/config/purple/app-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled /> --}}
@elseif (isset($demo) && $demo == 'saas')
    @vite(['resources/scss/config/saas/app.scss', 'resources/scss/config/saas/bootstrap.scss'])
    {{-- <link href="{{Vite::asset('resources/css/config/saas/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/saas/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/config/saas/bootstrap-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    <link href="{{Vite::asset('resources/css/config/saas/app-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled /> --}}
@else
    @vite(['resources/scss/config/default/app.scss', 'resources/scss/config/default/bootstrap.scss'])
    {{-- <link href="{{Vite::asset('resources/css/default/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/default/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{Vite::asset('resources/css/default/bootstrap-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    <link href="{{Vite::asset('resources/css/default/app-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled /> --}}
@endif
