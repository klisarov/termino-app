<x-layouts.dashboard title="Services">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Услуги</h1>
            <p class="mt-1 text-sm text-gray-500">
                Управувај со услугите кои се нудат од твојот бизнис.
            </p>
        </div>

        <a href="{{ route('dashboard.services.create') }}" class="btn-primary">
            Додади услуга
        </a>
    </div>

    <div class="mt-8">
        @if ($services->isEmpty())
            <div class="card p-10 text-center">
                <h2 class="text-lg font-semibold text-gray-900">
                    Немате услуги
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    Додади нова услуга за да започнеш со закажувања.
                </p>

                <a href="{{ route('dashboard.services.create') }}"
                   class="btn-primary mt-6 inline-block">
                    Додади услуга
                </a>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
                <div class="divide-y divide-gray-200">
                    @foreach ($services as $service)
                        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="font-semibold text-gray-900">
                                        {{ $service->name }}
                                    </h2>

                                    @if ($service->is_active)
                                        <span class="badge bg-green-100 text-green-700">
                                            Активна
                                        </span>
                                    @else
                                        <span class="badge bg-gray-100 text-gray-500">
                                            Деактивирана
                                        </span>
                                    @endif
                                </div>

                                @if ($service->description)
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $service->description }}
                                    </p>
                                @endif

                                <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-500">
                                    <span>
                                        {{ $service->duration_minutes }} мин
                                    </span>

                                    <span>
                                        {{ number_format($service->price, 0) }} MKD
                                    </span>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <a href="{{ route('dashboard.services.edit', $service) }}"
                                   class="btn-secondary">
                                    Измени
                                </a>

                                <form method="POST"
                                      action="{{ route('dashboard.services.toggle', $service) }}">
                                    @csrf

                                    <button type="submit" class="btn-secondary">
                                        {{ $service->is_active ? 'Деактивирај' : 'Активриај' }}
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('dashboard.services.destroy', $service) }}"
                                      onsubmit="return confirm('Delete this service? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="rounded-xl border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                        Избриши
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.dashboard>