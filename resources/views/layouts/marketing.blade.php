<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HelloRamp — AI vehicle photography that sells cars faster')</title>
    <meta name="description" content="@yield('description', 'Turn smartphone photos of your inventory into studio-quality listings in minutes. Clean backgrounds, clean reflections, consistent results.')">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-slate-900">
    <header class="border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg">
                <span class="inline-block w-8 h-8 rounded-md bg-slate-900 text-white grid place-items-center">H</span>
                HelloRamp
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm text-slate-700">
                <div class="relative group">
                    <button class="hover:text-slate-900 font-medium">Products</button>
                    <div class="absolute left-0 top-full pt-2 hidden group-hover:block z-20">
                        <div class="bg-white border border-slate-200 rounded-lg shadow-lg py-2 w-56">
                            <a href="{{ route('products.studio') }}" class="block px-4 py-2 hover:bg-slate-50"><strong>Studio</strong><div class="text-xs text-slate-500">AI vehicle photography</div></a>
                            <a href="{{ route('products.social') }}" class="block px-4 py-2 hover:bg-slate-50"><strong>Social</strong><div class="text-xs text-slate-500">Daily listings & posts</div></a>
                            <a href="{{ route('products.engage') }}" class="block px-4 py-2 hover:bg-slate-50"><strong>Engage</strong><div class="text-xs text-slate-500">Personal video replies</div></a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('solutions') }}" class="hover:text-slate-900">Solutions</a>
                <a href="{{ route('case-studies.index') }}" class="hover:text-slate-900">Case studies</a>
                <a href="{{ route('pricing') }}" class="hover:text-slate-900">Pricing</a>
                <a href="{{ route('contact.show') }}" class="hover:text-slate-900">Contact</a>
            </nav>
            <div class="flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="font-medium text-slate-700 hover:text-slate-900">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-medium text-slate-700 hover:text-slate-900">Log in</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-slate-900 text-white px-4 py-2 font-medium hover:bg-slate-700">Start free trial</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @if (session('status'))
            <div class="max-w-7xl mx-auto px-6 pt-4">
                <div class="rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2 text-sm">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-slate-200 mt-24">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-sm text-slate-600">
            <div>
                <div class="font-bold text-slate-900 mb-3">HelloRamp</div>
                <p>AI vehicle photography for dealerships.</p>
            </div>
            <div>
                <div class="font-semibold text-slate-900 mb-3">Products</div>
                <ul class="space-y-1">
                    <li><a href="{{ route('products.studio') }}">Studio</a></li>
                    <li><a href="{{ route('products.social') }}">Social</a></li>
                    <li><a href="{{ route('products.engage') }}">Engage</a></li>
                </ul>
            </div>
            <div>
                <div class="font-semibold text-slate-900 mb-3">Company</div>
                <ul class="space-y-1">
                    <li><a href="{{ route('solutions') }}">Solutions</a></li>
                    <li><a href="{{ route('case-studies.index') }}">Case studies</a></li>
                    <li><a href="{{ route('pricing') }}">Pricing</a></li>
                    <li><a href="{{ route('contact.show') }}">Contact</a></li>
                </ul>
            </div>
            <div>
                <div class="font-semibold text-slate-900 mb-3">Legal</div>
                <ul class="space-y-1">
                    <li>&copy; {{ date('Y') }} HelloRamp</li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
