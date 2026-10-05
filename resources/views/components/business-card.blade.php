@props(['tenant'])

<a href="{{ route('tenants.show', $tenant->slug) }}" class="card group flex flex-col overflow-hidden transition hover:shadow-md">
    <div class="flex h-36 items-center justify-center bg-gradient-to-br from-brand-100 to-brand-50 text-4xl font-bold text-brand-300">
        {{ strtoupper(substr($tenant->name, 0, 1)) }}
    </div>

    <div class="flex flex-1 flex-col gap-2 p-5">
        <div class="flex items-start justify-between gap-2">
            <h3 class="font-semibold text-gray-900 group-hover:text-brand-700">{{ $tenant->name }}</h3>
            @if ($tenant->category)
                <span class="badge bg-gray-100 text-gray-600 shrink-0">{{ $tenant->category->name }}</span>
            @endif
        </div>

        @if ($tenant->city)
            <p class="flex items-center gap-1 text-sm text-gray-500">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                {{ $tenant->city }}
            </p>
        @endif

        <div class="mt-auto flex items-center justify-between pt-2 text-sm">
            @if (isset($tenant->starting_price) && $tenant->starting_price !== null)
                <span class="font-medium text-gray-900">Од {{ number_format($tenant->starting_price, 0) }} MKD</span>
            @else
                <span class="text-gray-400">Види услуги</span>
            @endif

            @if (!empty($tenant->next_available))
                <span class="text-brand-600">Следно: {{ $tenant->next_available }}</span>
            @endif
        </div>
    </div>
</a>