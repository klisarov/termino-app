@props(['title' => null])

<x-layouts.base :title="$title">
    <div class="flex min-h-full">
        <aside class="hidden w-64 shrink-0 border-r border-gray-200 bg-white lg:flex lg:flex-col">
            <div class="flex h-16 items-center gap-2 border-b border-gray-200 px-6 text-lg font-bold text-gray-900">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-white">T</span>
                Termino
            </div>

            <nav class="flex-1 space-y-1 px-3 py-4 text-sm">
                @php
                    $links = [
                        ['route' => 'dashboard.index', 'label' => 'Dashboard'],
                        ['route' => 'dashboard.calendar.index', 'label' => 'Calendar'],
                        ['route' => 'dashboard.bookings.index', 'label' => 'Bookings'],
                        ['route' => 'dashboard.services.index', 'label' => 'Services'],
                        ['route' => 'dashboard.working-hours.index', 'label' => 'Working Hours'],
                        ['route' => 'dashboard.profile.edit', 'label' => 'Business Profile'],
                        ['route' => 'dashboard.customers.index', 'label' => 'Customers'],
                    ];
                @endphp

                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="block rounded-xl px-3 py-2 font-medium {{ request()->routeIs($link['route'].'*') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            @if (auth()->user()->tenant)
                <div class="border-t border-gray-200 p-4">
                    <a href="{{ route('tenants.show', auth()->user()->tenant->slug) }}" target="_blank"
                       class="block rounded-xl bg-gray-50 px-3 py-2 text-xs text-gray-500 hover:bg-gray-100">
                        View public page
                        <span class="block truncate font-medium text-gray-700">termino.mk/{{ auth()->user()->tenant->slug }}</span>
                    </a>
                </div>
            @endif
        </aside>

        <div class="flex flex-1 flex-col">
            <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
                <p class="text-sm font-medium text-gray-500 lg:hidden">Termino</p>
                <div class="ml-auto flex items-center gap-4">
                    <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-secondary !px-3 !py-1.5 text-xs">Log out</button>
                    </form>
                </div>
            </header>

            <x-ui.flash />

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-layouts.base>