<x-layouts.dashboard title="Dashboard">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Здраво, {{ auth()->user()->name }}!
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Еве што се случува со {{ $tenant->name }}.
        </p>
    </div>


    {{-- Stats --}}
    <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">

        <a
            href="{{ route('dashboard.bookings.index', ['filter' => 'today']) }}"
            class="card p-5 transition hover:shadow-md"
        >
            <p class="text-sm font-medium text-gray-500">
                Денешни резервации
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $todayCount }}
            </p>

            <p class="mt-1 text-xs text-gray-400">
                Планирано за денес
            </p>
        </a>


        <a
            href="{{ route('dashboard.bookings.index') }}"
            class="card p-5 transition hover:shadow-md"
        >
            <p class="text-sm font-medium text-gray-500">
                Следни резервации
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $upcomingCount }}
            </p>

            <p class="mt-1 text-xs text-gray-400">
                Активни резервации
            </p>
        </a>


        <a
            href="{{ route('dashboard.services.index') }}"
            class="card p-5 transition hover:shadow-md"
        >
            <p class="text-sm font-medium text-gray-500">
                Услуги
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $servicesCount }}
            </p>

            <p class="mt-1 text-xs text-gray-400">
                Твои услуги
            </p>
        </a>

    </div>


    {{-- Today's bookings --}}
    <div class="mt-8">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Денешни резервации
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    {{ now()->format('l, d F Y') }}
                </p>
            </div>

            <a
                href="{{ route('dashboard.bookings.index', ['filter' => 'today']) }}"
                class="text-sm font-medium text-brand-600 hover:text-brand-700"
            >
                Види се &rarr;
            </a>

        </div>


        <div class="mt-4">

            @if ($todaysBookings->isEmpty())

                <div class="card p-10 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>

                    </div>


                    <h3 class="mt-4 font-semibold text-gray-900">
                        Нема резервации денес.
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Немаш никакви резервации за денес.
                    </p>

                </div>

            @else

                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">

                    <div class="divide-y divide-gray-200">

                        @foreach ($todaysBookings as $booking)

                            <a
                                href="{{ route('dashboard.bookings.show', $booking) }}"
                                class="flex items-center justify-between gap-4 p-5 transition hover:bg-gray-50"
                            >

                                <div class="flex min-w-0 items-center gap-4">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-sm font-semibold text-brand-700">
                                        {{ strtoupper(substr($booking->customer->name, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">

                                        <p class="truncate font-medium text-gray-900">
                                            {{ $booking->customer->name }}
                                        </p>

                                        <p class="mt-0.5 truncate text-sm text-gray-500">
                                            {{ $booking->service->name }}
                                        </p>

                                    </div>

                                </div>


                                <div class="shrink-0 text-right">

                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-400">
                                        {{ $booking->service->duration_minutes }} мин
                                    </p>

                                </div>

                            </a>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- Quick actions --}}
    <div class="mt-8">

        <h2 class="text-lg font-semibold text-gray-900">
            Брзи акции
        </h2>


        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">


            {{-- Add service --}}
            <a
                href="{{ route('dashboard.services.create') }}"
                class="card flex items-center gap-4 p-5 transition hover:shadow-md"
            >

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">

                    <span class="text-lg font-medium leading-none">
                        +
                    </span>

                </div>


                <div>

                    <p class="font-medium text-gray-900">
                        Додади услуга
                    </p>

                    <p class="text-sm text-gray-500">
                        Создади нова услуга
                    </p>

                </div>

            </a>


            {{-- Open calendar --}}
            <a
                href="{{ route('dashboard.calendar.index') }}"
                class="card flex items-center gap-4 p-5 transition hover:shadow-md"
            >

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">

                    <svg
                        class="h-3.5 w-3.5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>

                </div>


                <div>

                    <p class="font-medium text-gray-900">
                        Отвори календар
                    </p>

                    <p class="text-sm text-gray-500">
                        Прегледај го твојот календар
                    </p>

                </div>

            </a>


            {{-- View booking page --}}
            <a
                href="{{ route('tenants.show', $tenant->slug) }}"
                target="_blank"
                class="card flex items-center gap-4 p-5 transition hover:shadow-md"
            >

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">

                    <span class="text-sm leading-none">
                        ↗
                    </span>

                </div>


                <div>

                    <p class="font-medium text-gray-900">
                        Види booking page
                    </p>

                    <p class="text-sm text-gray-500">
                        Гледај што гледа клиентот
                    </p>

                </div>

            </a>

        </div>

    </div>

</x-layouts.dashboard>