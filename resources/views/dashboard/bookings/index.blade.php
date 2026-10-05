<x-layouts.dashboard title="Bookings">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Bookings
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Управувај со закажувања и клиенти.
                </p>
            </div>

            <a
                href="{{ route('dashboard.calendar.index') }}"
                class="btn-secondary"
            >
                Види календар
            </a>
        </div>


        {{-- Filters --}}
        <div class="flex flex-wrap gap-2">

            @php
                $filters = [
                    'upcoming' => 'Наскоро',
                    'today' => 'Денес',
                    'past' => 'Минато',
                ];
            @endphp

            @foreach ($filters as $value => $label)
                <a
                    href="{{ route('dashboard.bookings.index', ['filter' => $value]) }}"
                    class="rounded-xl px-4 py-2 text-sm font-medium transition
                        {{ $filter === $value
                            ? 'bg-gray-900 text-white'
                            : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50' }}"
                >
                    {{ $label }}
                </a>
            @endforeach

        </div>


        {{-- Bookings --}}
        <div class="card overflow-hidden">

            @if ($bookings->isEmpty())

                {{-- Empty state --}}
                <div class="px-6 py-16 text-center">

                    {{-- Small calendar icon --}}
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-400">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            class="h-4 w-4"
                        >
                            <rect
                                x="3.5"
                                y="5"
                                width="17"
                                height="16"
                                rx="2"
                            />

                            <path
                                stroke-linecap="round"
                                d="M7 3.5V7M17 3.5V7M3.5 9.5h17"
                            />
                        </svg>

                    </div>


                    <h2 class="mt-4 text-sm font-semibold text-gray-900">
                        Нема најдени закажувања
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        @if ($filter === 'today')
                            Немате закажувања за денес.
                        @elseif ($filter === 'past')
                            Немате минати закажувања.
                        @else
                            Немате закажувања.
                        @endif
                    </p>

                </div>

            @else

                {{-- Desktop table --}}
                <div class="hidden overflow-x-auto md:block">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Клиент
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Услуга
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Датум
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Време
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Статус
                                </th>

                                <th class="px-6 py-3"></th>

                            </tr>
                        </thead>


                        <tbody class="divide-y divide-gray-100 bg-white">

                            @foreach ($bookings as $booking)

                                @php
                                    $statusClasses = match ($booking->status) {
                                        \App\Models\Booking::STATUS_BOOKED =>
                                            'bg-green-50 text-green-700',

                                        \App\Models\Booking::STATUS_COMPLETED =>
                                            'bg-blue-50 text-blue-700',

                                        \App\Models\Booking::STATUS_CANCELLED =>
                                            'bg-red-50 text-red-700',

                                        default =>
                                            'bg-gray-100 text-gray-600',
                                    };
                                @endphp


                                <tr class="transition hover:bg-gray-50">

                                    {{-- Customer --}}
                                    <td class="px-6 py-4">

                                        <p class="font-medium text-gray-900">
                                            {{ $booking->customer->name ?? 'Клиент' }}
                                        </p>

                                        @if ($booking->customer?->phone)
                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ $booking->customer->phone }}
                                            </p>
                                        @endif

                                    </td>


                                    {{-- Service --}}
                                    <td class="px-6 py-4">

                                        <p class="text-sm text-gray-700">
                                            {{ $booking->service->name ?? 'Услуга' }}
                                        </p>

                                        @if ($booking->service)
                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ $booking->service->duration_minutes }} мин
                                            </p>
                                        @endif

                                    </td>


                                    {{-- Date --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        {{ $booking->booking_date->format('d M Y') }}

                                    </td>


                                    {{-- Time --}}
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">

                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}

                                    </td>


                                    {{-- Status --}}
                                    <td class="px-6 py-4">

                                        <span
                                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClasses }}"
                                        >
                                            {{ ucfirst($booking->status) }}
                                        </span>

                                    </td>


                                    {{-- View --}}
                                    <td class="px-6 py-4 text-right">

                                        <a
                                            href="{{ route('dashboard.bookings.show', $booking) }}"
                                            class="text-sm font-medium text-brand-600 hover:text-brand-700"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Mobile cards --}}
                <div class="divide-y divide-gray-100 md:hidden">

                    @foreach ($bookings as $booking)

                        @php
                            $statusClasses = match ($booking->status) {
                                \App\Models\Booking::STATUS_BOOKED =>
                                    'bg-green-50 text-green-700',

                                \App\Models\Booking::STATUS_COMPLETED =>
                                    'bg-blue-50 text-blue-700',

                                \App\Models\Booking::STATUS_CANCELLED =>
                                    'bg-red-50 text-red-700',

                                default =>
                                    'bg-gray-100 text-gray-600',
                            };
                        @endphp


                        <a
                            href="{{ route('dashboard.bookings.show', $booking) }}"
                            class="block p-5 transition hover:bg-gray-50"
                        >

                            <div class="flex items-start justify-between gap-4">

                                <div class="min-w-0">

                                    <p class="truncate font-semibold text-gray-900">
                                        {{ $booking->customer->name ?? 'Клиент' }}
                                    </p>

                                    <p class="mt-1 truncate text-sm text-gray-500">
                                        {{ $booking->service->name ?? 'Услуга' }}
                                    </p>

                                </div>


                                <span
                                    class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClasses }}"
                                >
                                    {{ ucfirst($booking->status) }}
                                </span>

                            </div>


                            <div class="mt-4 flex items-center justify-between text-sm">

                                <div>

                                    <p class="font-medium text-gray-900">
                                        {{ $booking->booking_date->format('d M Y') }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                    </p>

                                </div>


                                @if ($booking->service)

                                    <p class="font-medium text-gray-900">
                                        {{ number_format($booking->service->price, 0) }} MKD
                                    </p>

                                @endif

                            </div>

                        </a>

                    @endforeach

                </div>

            @endif

        </div>


        {{-- Pagination --}}
        @if ($bookings->hasPages())

            <div>
                {{ $bookings->links() }}
            </div>

        @endif

    </div>
</x-layouts.dashboard>