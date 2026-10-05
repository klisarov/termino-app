@props(['title' => null])

<x-layouts.base :title="$title">
    <div class="flex min-h-full flex-col">
        <header class="border-b border-gray-200 bg-white/80 backdrop-blur sticky top-0 z-30">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold text-gray-900">
                    Termino
                </a>

                <div class="hidden items-center gap-6 text-sm font-medium text-gray-600 md:flex">
                    <a href="{{ route('marketplace.index') }}" class="hover:text-gray-900">Истражи</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.index') }}" class="btn-secondary">Admin панел</a>
                        @else
                            <a href="{{ route('dashboard.index') }}" class="btn-secondary">Главна страна</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="hidden text-sm font-medium text-gray-600 hover:text-gray-900 sm:block">Log in</a>
                        <a href="{{ route('register') }}" class="btn-primary">Креирај страна за твојот бизнис</a>
                    @endauth
                </div>
            </nav>
        </header>

        <x-ui.flash />

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 text-sm text-gray-500 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <p>&copy; {{ date('Y') }} Termino. Најди услуга денес.</p>
                    <div class="flex gap-6">
                        <a href="{{ route('marketplace.index') }}" class="hover:text-gray-700">Истражи</a>
                        <a href="{{ route('register') }}" class="hover:text-gray-700">Додади го твојот бизнис</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</x-layouts.base>