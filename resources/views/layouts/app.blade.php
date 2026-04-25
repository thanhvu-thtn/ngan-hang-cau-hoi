<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $title ?? 'Ngân hàng câu hỏi' }}</title>
</head>
<body class="bg-gray-100"> <div class="min-h-full flex flex-col h-screen">
        
        @include('layouts.navigation')

        @if (isset($header))
            <header class="relative bg-white shadow-sm">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }} 
                </div>
            </header>
        @endif

        <main class="flex-grow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        @include('layouts.footer')

    </div>
</body>
</html>