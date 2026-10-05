@props(['title' => null])

@php
    $tenant = auth()->user()->tenant;
@endphp

<x-layouts.base :title="$title">
    <div class="flex min-h-full">

        {{-- Sidebar --}}
        <aside class="hidden w-64 shrink-0 border-r border-gray-200 bg-white lg:flex lg:flex-col">

            {{-- Logo --}}
            <div class="flex h-16 items-center gap-2 border-b border-gray-200 px-6 text-lg font-bold text-gray-900">

                Termino
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-1 px-3 py-4 text-sm">

                @php
                    $links = [
                        [
                            'route' => 'dashboard.index',
                            'label' => 'Преглед',
                        ],
                        [
                            'route' => 'dashboard.calendar.index',
                            'label' => 'Календар',
                        ],
                        [
                            'route' => 'dashboard.bookings.index',
                            'label' => 'Закажувања',
                        ],
                        [
                            'route' => 'dashboard.services.index',
                            'label' => 'Услуги',
                        ],
                        [
                            'route' => 'dashboard.working-hours.index',
                            'label' => 'Работно време',
                        ],
                        [
                            'route' => 'dashboard.customers.index',
                            'label' => 'Клиенти',
                        ],
                        [
                            'route' => 'dashboard.profile.edit',
                            'label' => 'Профил',
                        ],
                    ];
                @endphp

                @foreach ($links as $link)
                    <a
                        href="{{ route($link['route']) }}"
                        class="block rounded-xl px-3 py-2 font-medium transition
                        {{ request()->routeIs($link['route'] . '*')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach

            </nav>

            {{-- Sidebar bottom --}}
            <div class="border-t border-gray-200 p-4">

                <div class="mb-3 px-3">
                    <p class="truncate text-sm font-medium text-gray-900">
                        {{ $tenant->name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        Business сметка
                    </p>
                </div>

                <a
                    href="{{ route('tenants.show', $tenant->slug) }}"
                    target="_blank"
                    class="mb-2 block rounded-xl bg-gray-50 px-3 py-2 text-xs font-medium text-gray-500 transition hover:bg-gray-100"
                >
                    Види страна за закажувања ↗
                </a>

                <a
                    href="{{ route('home') }}"
                    class="block rounded-xl bg-gray-50 px-3 py-2 text-xs font-medium text-gray-500 transition hover:bg-gray-100"
                >
                    &larr; Назад кон Termino
                </a>

            </div>

        </aside>

        {{-- Main area --}}
        <div class="flex flex-1 flex-col">

            {{-- Top bar --}}
            <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">

                {{-- Mobile title --}}
                <div class="lg:hidden">
                    <p class="text-sm font-bold text-gray-900">
                        Termino
                    </p>
                </div>

                {{-- User --}}
                <div class="ml-auto flex items-center gap-4">

                    <span class="hidden text-sm text-gray-600 sm:block">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="btn-secondary !px-3 !py-1.5 text-xs"
                        >
                            Log out
                        </button>
                    </form>

                </div>

            </header>

            {{-- Flash messages --}}
            <x-ui.flash />

            {{-- Page content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

        </div>

    </div>
</x-layouts.base>