<x-layouts.dashboard title="Booking details">
    <div class="mx-auto max-w-3xl space-y-6">

        {{-- Header --}}
        <div>
            <a
                href="{{ route('dashboard.bookings.index') }}"
                class="text-sm text-gray-500 hover:text-gray-700"
            >
                &larr; Back to bookings
            </a>

            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">
                    Booking details
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    View and manage this appointment.
                </p>
            </div>
        </div>

        {{-- Status --}}
        <div class="card p-6">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-brand-600">
                        Appointment
                    </p>

                    <h2 class="mt-1 text-xl font-semibold text-gray-900">
                        {{ $booking->service->name ?? 'Service' }}
                    </h2>
                </div>

                @php
                    $statusClasses = match ($booking->status) {
                        \App\Models\Booking::STATUS_BOOKED =>
                            'bg-green-50 text-green-700 ring-green-200',

                        \App\Models\Booking::STATUS_COMPLETED =>
                            'bg-blue-50 text-blue-700 ring-blue-200',

                        \App\Models\Booking::STATUS_CANCELLED =>
                            'bg-red-50 text-red-700 ring-red-200',

                        default =>
                            'bg-gray-100 text-gray-600 ring-gray-200',
                    };
                @endphp

                <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClasses }}">
                    {{ ucfirst($booking->status) }}
                </span>

            </div>

            {{-- Appointment information --}}
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs font-medium text-gray-400">
                        Date
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ $booking->booking_date->format('l, d F Y') }}
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs font-medium text-gray-400">
                        Time
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                    </p>
                </div>

                @if ($booking->service)
                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs font-medium text-gray-400">
                            Duration
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $booking->service->duration_minutes }} minutes
                        </p>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs font-medium text-gray-400">
                            Price
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ number_format($booking->service->price, 0) }} MKD
                        </p>
                    </div>
                @endif

            </div>
        </div>

        {{-- Customer --}}
        <div class="card p-6">

            <h2 class="text-lg font-semibold text-gray-900">
                Customer
            </h2>

            @if ($booking->customer)

                <div class="mt-5 space-y-4">

                    <div>
                        <p class="text-xs font-medium text-gray-400">
                            Name
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900">
                            {{ $booking->customer->name }}
                        </p>
                    </div>

                    @if ($booking->customer->phone)
                        <div>
                            <p class="text-xs font-medium text-gray-400">
                                Phone
                            </p>

                            <a
                                href="tel:{{ $booking->customer->phone }}"
                                class="mt-1 block text-sm text-brand-600 hover:text-brand-700"
                            >
                                {{ $booking->customer->phone }}
                            </a>
                        </div>
                    @endif

                    @if ($booking->customer->email)
                        <div>
                            <p class="text-xs font-medium text-gray-400">
                                Email
                            </p>

                            <a
                                href="mailto:{{ $booking->customer->email }}"
                                class="mt-1 block text-sm text-brand-600 hover:text-brand-700"
                            >
                                {{ $booking->customer->email }}
                            </a>
                        </div>
                    @endif

                    @if ($booking->customer->address || $booking->customer->city)
                        <div>
                            <p class="text-xs font-medium text-gray-400">
                                Address
                            </p>

                            <p class="mt-1 text-sm text-gray-700">
                                {{ $booking->customer->address }}
                                @if ($booking->customer->address && $booking->customer->city)
                                    ,
                                @endif
                                {{ $booking->customer->city }}
                            </p>
                        </div>
                    @endif

                </div>

            @else

                <p class="mt-4 text-sm text-gray-500">
                    Customer information is unavailable.
                </p>

            @endif

        </div>

        {{-- Customer note --}}
        @if ($booking->customer_note)
            <div class="card p-6">

                <h2 class="text-lg font-semibold text-gray-900">
                    Customer note
                </h2>

                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-600">
                    {{ $booking->customer_note }}
                </p>

            </div>
        @endif

        {{-- Actions --}}
        @if ($booking->status === \App\Models\Booking::STATUS_BOOKED)

            <div class="card p-6">

                <h2 class="text-lg font-semibold text-gray-900">
                    Actions
                </h2>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row">

                    <form
                        method="POST"
                        action="{{ route('dashboard.bookings.complete', $booking) }}"
                        class="flex-1"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn-primary w-full"
                        >
                            Mark as completed
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('dashboard.bookings.cancel', $booking) }}"
                        class="flex-1"
                        onsubmit="return confirm('Are you sure you want to cancel this booking?')"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50"
                        >
                            Cancel booking
                        </button>
                    </form>

                </div>

            </div>

        @endif

    </div>
</x-layouts.dashboard>