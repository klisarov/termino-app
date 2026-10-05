<x-layouts.dashboard title="Calendar">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Calendar</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Управувај со закажувања и достапност.
                </p>
            </div>

            <a href="{{ route('dashboard.bookings.index') }}" class="btn-secondary">
                Види закажувања
            </a>
        </div>

        {{-- Calendar --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Month calendar --}}
            <div class="card p-5 lg:col-span-2">

                {{-- Month navigation --}}
                <div class="flex items-center justify-between">
                    <a
                        href="{{ route('dashboard.calendar.index', [
                            'month' => $month->subMonth()->format('Y-m-01'),
                            'date' => $month->subMonth()->format('Y-m-01'),
                        ]) }}"
                        class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
                    >
                        &larr;
                    </a>

                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $month->format('F Y') }}
                    </h2>

                    <a
                        href="{{ route('dashboard.calendar.index', [
                            'month' => $month->addMonth()->format('Y-m-01'),
                            'date' => $month->addMonth()->format('Y-m-01'),
                        ]) }}"
                        class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
                    >
                        &rarr;
                    </a>
                </div>

                {{-- Weekdays --}}
                <div class="mt-6 grid grid-cols-7 text-center text-xs font-medium text-gray-400">
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                    <div>Sun</div>
                </div>

                @php
                    $firstDay = $month->startOfMonth();
                    $daysInMonth = $month->daysInMonth;

                    // Carbon: Sunday = 0, Monday = 1
                    $startOffset = $firstDay->dayOfWeekIso - 1;
                @endphp

                <div class="mt-2 grid grid-cols-7 gap-1">

                    {{-- Empty cells before month starts --}}
                    @for ($i = 0; $i < $startOffset; $i++)
                        <div class="min-h-24 rounded-lg bg-gray-50"></div>
                    @endfor

                    {{-- Days --}}
                    @for ($day = 1; $day <= $daysInMonth; $day++)

                        @php
                            $date = $month->setDay($day);
                            $dateString = $date->toDateString();

                            $dayBookings = $bookings->get($dateString, collect());

                            $isSelected = $selectedDate->toDateString() === $dateString;
                            $isToday = now()->toDateString() === $dateString;
                        @endphp

                        <a
                            href="{{ route('dashboard.calendar.index', [
                                'month' => $month->format('Y-m-01'),
                                'date' => $dateString,
                            ]) }}"
                            class="min-h-24 rounded-lg border p-2 transition
                                {{ $isSelected
                                    ? 'border-brand-500 bg-brand-50'
                                    : 'border-gray-100 bg-white hover:border-gray-300 hover:bg-gray-50' }}"
                        >

                            {{-- Day number --}}
                            <div class="flex items-center justify-between">
                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-full text-sm
                                        {{ $isToday
                                            ? 'bg-gray-900 font-bold text-white'
                                            : ($isSelected
                                                ? 'font-bold text-brand-700'
                                                : 'text-gray-700') }}"
                                >
                                    {{ $day }}
                                </span>

                                @if ($dayBookings->count())
                                    <span class="text-xs font-medium text-gray-400">
                                        {{ $dayBookings->count() }}
                                    </span>
                                @endif
                            </div>

                            {{-- Booking previews --}}
                            <div class="mt-2 space-y-1">
                                @foreach ($dayBookings->take(2) as $booking)
                                    <div class="truncate rounded-md bg-gray-100 px-1.5 py-1 text-xs text-gray-600">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                        ·
                                        {{ $booking->customer->name ?? 'Customer' }}
                                    </div>
                                @endforeach

                                @if ($dayBookings->count() > 2)
                                    <div class="text-xs font-medium text-brand-600">
                                        +{{ $dayBookings->count() - 2 }} more
                                    </div>
                                @endif
                            </div>

                        </a>
                    @endfor

                </div>
            </div>

            {{-- Selected day --}}
            <div class="card p-5">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-brand-600">
                            Избран ден
                        </p>

                        <h2 class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $selectedDate->format('l') }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            {{ $selectedDate->format('d F Y') }}
                        </p>
                    </div>

                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                        {{ $selectedDayBookings->count() }}
                        {{ \Illuminate\Support\Str::plural('booking', $selectedDayBookings->count()) }}
                    </span>
                </div>

                <div class="mt-6">

                    @if ($selectedDayBookings->isEmpty())

                        <div class="rounded-xl bg-gray-50 px-4 py-8 text-center">
                            <p class="text-sm font-medium text-gray-700">
                                Нема закажувања
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Нема закажани термини за овој ден.
                            </p>
                        </div>

                    @else

                        <div class="space-y-3">

                            @foreach ($selectedDayBookings as $booking)

                                <a
                                    href="{{ route('dashboard.bookings.show', $booking) }}"
                                    class="block rounded-xl border border-gray-200 p-4 transition hover:border-gray-300 hover:bg-gray-50"
                                >
                                    <div class="flex items-start justify-between gap-3">

                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ $booking->customer->name ?? 'Customer' }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ $booking->service->name ?? 'Service' }}
                                            </p>
                                        </div>

                                        <span class="shrink-0 text-sm font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                        </span>

                                    </div>

                                    @if ($booking->service)
                                        <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                                            <span>
                                                {{ $booking->service->duration_minutes }} min
                                            </span>

                                            <span>
                                                {{ number_format($booking->service->price, 0) }} MKD
                                            </span>
                                        </div>
                                    @endif

                                </a>

                            @endforeach

                        </div>

                    @endif

                </div>
            </div>
        </div>

    </div>
</x-layouts.dashboard>