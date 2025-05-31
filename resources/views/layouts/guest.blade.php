<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name='author' content='Mark Jason Penote Espelita'>
        <meta name='keywords' content='Inventory Management System, IMS, ISP'>
        <meta name='description' content='Efficient inventory management system for Librify IT Solutions, designed to streamline operations, track network equipment, and optimize resource allocation to ensure seamless service delivery and reduce operational costs.'>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <style>
            body > div > div {
                background: linear-gradient(45deg, #111827 60%, #F38E1A 100% );
            }
            body button {
                background: #F88B1D !important;
                color: black !important;
            }
        </style>
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
