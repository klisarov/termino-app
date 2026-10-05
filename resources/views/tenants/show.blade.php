<x-layouts.public :title="$tenant->name">
    <div class="border-b border-gray-200 bg-white">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-100 to-brand-50 text-3xl font-bold text-brand-400">
                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $tenant->name }}</h1>
                        @if ($tenant->category)
                            <span class="badge bg-gray-100 text-gray-600">{{ $tenant->category->name }}</span>
                        @endif
                    </div>
                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                        @if ($tenant->city)
                            <span>📍 {{ $tenant->address ? $tenant->address.', ' : '' }}{{ $tenant->city }}</span>
                        @endif
                        @if ($tenant->phone)
                            <span>📞 {{ $tenant->phone }}</span>
                        @endif
                        @if ($tenant->email)
                            <span>✉️ {{ $tenant->email }}</span>
                        @endif
                    </div>
                </div>
            </div>

            @if ($tenant->description)
                <p class="mt-6 max-w-3xl text-gray-600">{{ $tenant->description }}</p>
            @endif
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <h2 class="text-lg font-semibold text-gray-900">Услуги</h2>

        @if ($tenant->services->isEmpty())
            <p class="mt-4 text-gray-500">Овој бизнис нема додадено услуга.</p>
        @else
            <div class="mt-4 divide-y divide-gray-200 overflow-hidden rounded-2xl border border-gray-200 bg-white">
                @foreach ($tenant->services as $service)
                    <div class="flex items-center justify-between gap-4 p-5">
                        <div>
                            <p class="font-medium text-gray-900">{{ $service->name }}</p>
                            <p class="mt-0.5 text-sm text-gray-500">
                                {{ $service->duration_minutes }} min
                                @if ($service->description) &middot; {{ $service->description }} @endif
                            </p>
                        </div>
                        <div class="flex shrink-0 items-center gap-4">
                            <span class="font-semibold text-gray-900">{{ number_format($service->price, 0) }} MKD</span>
                            <a href="{{ route('tenants.book', ['tenant' => $tenant->slug, 'service_id' => $service->id]) }}"
                               class="btn-primary">Одбери</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.public>