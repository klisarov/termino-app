<x-layouts.admin title="Admin Dashboard">

    <div class="space-y-8">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Admin Контролна Табла
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Преглед на Termino.
            </p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

            <div class="card p-5">
                <p class="text-sm font-medium text-gray-500">
                    Вкупно бизниси
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $stats['total_tenants'] }}
                </p>
            </div>

            <div class="card p-5">
                <p class="text-sm font-medium text-gray-500">
                    Активни бизниси
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $stats['active_tenants'] }}
                </p>
            </div>

            <div class="card p-5">
                <p class="text-sm font-medium text-gray-500">
                    Вкупно закажувања
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $stats['total_bookings'] }}
                </p>
            </div>

            <div class="card p-5">
                <p class="text-sm font-medium text-gray-500">
                    Клиенти
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $stats['total_customers'] }}
                </p>
            </div>

        </div>

        {{-- Recent businesses --}}
        <div>

            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Ново регистрирани бизниси
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Ново регистрирани бизниси на Termino.
                    </p>
                </div>

                <a
                    href="{{ route('admin.tenants.index') }}"
                    class="text-sm font-medium text-brand-600 hover:text-brand-700"
                >
                    Види се
                </a>
            </div>

            <div class="card mt-4 overflow-hidden">

                @if ($recentTenants->isEmpty())

                    <div class="p-8 text-center text-sm text-gray-500">
                        Уште нема бизниси.
                    </div>

                @else

                    <div class="divide-y divide-gray-200">

                        @foreach ($recentTenants as $tenant)

                            <div class="flex items-center justify-between gap-4 p-5">

                                <div class="min-w-0">

                                    <p class="truncate font-medium text-gray-900">
                                        {{ $tenant->name }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $tenant->category?->name ?? 'Нема категорија' }}
                                        @if ($tenant->city)
                                            · {{ $tenant->city }}
                                        @endif
                                    </p>

                                </div>

                                <span class="badge shrink-0 bg-green-100 text-green-700">
                                    Активни
                                </span>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

        {{-- Recent bookings --}}
        <div>

            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Последни закажувања
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Последни закажувања во целата платформа.
                </p>
            </div>

            <div class="card mt-4 overflow-hidden">

                @if ($recentBookings->isEmpty())

                    <div class="p-8 text-center text-sm text-gray-500">
                        Нема закажувања.
                    </div>

                @else

                    <div class="divide-y divide-gray-200">

                        @foreach ($recentBookings as $booking)

                            <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">

                                <div>

                                    <p class="font-medium text-gray-900">
                                        {{ $booking->service?->name ?? 'Booking' }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $booking->tenant?->name ?? 'Unknown business' }}
                                    </p>

                                </div>

                                <div class="text-sm text-gray-500 sm:text-right">

                                    @if ($booking->booking_date)
                                        <p>
                                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                        </p>
                                    @endif

                                    @if ($booking->start_time)
                                        <p class="mt-1">
                                            {{ $booking->start_time }}
                                        </p>
                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-layouts.admin>