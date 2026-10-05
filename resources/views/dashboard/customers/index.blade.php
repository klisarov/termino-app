<x-layouts.dashboard title="Customers">

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Клиенти
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Преглед на клиенти кои закажувале кај вашиот бизнис.
            </p>
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('dashboard.customers.index') }}">
            <div class="max-w-md">
                <label for="search" class="sr-only">
                    Search customers
                </label>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg
                            class="h-4 w-4 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                            />
                        </svg>
                    </div>

                    <input
                        id="search"
                        type="search"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Пребарај по име, телефон или email..."
                        class="input pl-10"
                    >
                </div>
            </div>
        </form>

        {{-- Customers --}}
        <div class="card overflow-hidden">

            @if ($customers->isEmpty())

                {{-- Empty state --}}
                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
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
                                d="M15 19a6 6 0 0 0-12 0m6-8a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7-3v6m3-3h-6"
                            />
                        </svg>
                    </div>

                    <h2 class="mt-4 text-sm font-semibold text-gray-900">
                        {{ !empty($search) ? 'Не се пронајдени клиенти' : 'Нема клиенти уште' }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        @if (!empty($search))
                            Пробај да пребараш со друго име, телефон или email.
                        @else
                            Клиенти ќе се појават овде кога ќе направат закажување.
                        @endif
                    </p>

                    @if (!empty($search))
                        <a
                            href="{{ route('dashboard.customers.index') }}"
                            class="mt-4 inline-block text-sm font-medium text-brand-600 hover:text-brand-700"
                        >
                            Чисто пребарување
                        </a>
                    @endif

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
                                    Контакт
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Закажувања
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Последно закажување
                                </th>

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">

                            @foreach ($customers as $customer)

                                @php
                                    $latestBooking = $customer->bookings->first();
                                @endphp

                                <tr class="transition hover:bg-gray-50">

                                    {{-- Customer --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-sm font-semibold text-brand-700">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>

                                            <div class="min-w-0">

                                                <p class="truncate font-medium text-gray-900">
                                                    {{ $customer->name }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- Contact --}}
                                    <td class="px-6 py-4">

                                        @if ($customer->phone)
                                            <p class="text-sm text-gray-700">
                                                {{ $customer->phone }}
                                            </p>
                                        @endif

                                        @if ($customer->email)
                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ $customer->email }}
                                            </p>
                                        @endif

                                        @if (!$customer->phone && !$customer->email)
                                            <span class="text-sm text-gray-400">
                                                —
                                            </span>
                                        @endif

                                    </td>

                                    {{-- Bookings --}}
                                    <td class="px-6 py-4">

                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $customer->bookings_count }}
                                        </span>

                                    </td>

                                    {{-- Last booking --}}
                                    <td class="px-6 py-4">

                                        @if ($latestBooking)

                                            <p class="text-sm text-gray-700">
                                                {{ $latestBooking->booking_date->format('d M Y') }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($latestBooking->start_time)->format('H:i') }}
                                            </p>

                                        @else

                                            <span class="text-sm text-gray-400">
                                                No bookings
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Mobile cards --}}
                <div class="divide-y divide-gray-100 md:hidden">

                    @foreach ($customers as $customer)

                        @php
                            $latestBooking = $customer->bookings->first();
                        @endphp

                        <div class="p-5">

                            <div class="flex items-start gap-3">

                                {{-- Avatar --}}
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-sm font-semibold text-brand-700">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>

                                {{-- Details --}}
                                <div class="min-w-0 flex-1">

                                    <p class="truncate font-semibold text-gray-900">
                                        {{ $customer->name }}
                                    </p>

                                    @if ($customer->phone)
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $customer->phone }}
                                        </p>
                                    @endif

                                    @if ($customer->email)
                                        <p class="mt-0.5 truncate text-sm text-gray-500">
                                            {{ $customer->email }}
                                        </p>
                                    @endif

                                    <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400">

                                        <span>
                                            {{ $customer->bookings_count }}
                                            {{ $customer->bookings_count === 1 ? 'booking' : 'bookings' }}
                                        </span>

                                        @if ($latestBooking)
                                            <span>
                                                Last:
                                                {{ $latestBooking->booking_date->format('d M Y') }}
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

        {{-- Pagination --}}
        @if ($customers->hasPages())

            <div>
                {{ $customers->links() }}
            </div>

        @endif

    </div>

</x-layouts.dashboard>