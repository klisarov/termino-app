<x-layouts.public :title="'Booking confirmed - ' . $tenant->name">
    <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="card p-6 text-center sm:p-10">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-50 text-green-600 ring-1 ring-inset ring-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-7 w-7" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                </svg>
            </div>

            <p class="mt-5 text-sm font-medium uppercase tracking-wide text-brand-600">
                {{ $tenant->name }}
            </p>

            <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                Успешно закажавте услуга кај {{ $tenant->name }}!
            </h1>

            <p class="mt-2 text-gray-500">
                Вашиот термин е успешно закажан.
            </p>

            <div class="mt-8 grid gap-4 text-left sm:grid-cols-2">
                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs font-medium text-gray-400">Услуга</p>
                    <p class="mt-1 font-medium text-gray-900">{{ $booking->service->name }}</p>
                </div>

                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs font-medium text-gray-400">Датум</p>
                    <p class="mt-1 font-medium text-gray-900">{{ $booking->booking_date->format('l, d F Y') }}</p>
                </div>

                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs font-medium text-gray-400">Време</p>
                    <p class="mt-1 font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs font-medium text-gray-400">Времетраење на услугата</p>
                    <p class="mt-1 font-medium text-gray-900">{{ $booking->service->duration_minutes }} minutes</p>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-100 pt-6 text-sm text-gray-500">
                Референтен код за резервацијата:
                <span class="font-medium text-gray-700">{{ $booking->public_id }}</span>
            </div>

            <a href="{{ route('tenants.show', $tenant->slug) }}" class="btn-primary mt-6 inline-flex">
                Назад кон {{ $tenant->name }}
            </a>
        </div>
    </div>
</x-layouts.public>
