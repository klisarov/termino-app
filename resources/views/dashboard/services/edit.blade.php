<x-layouts.dashboard title="Edit service">
    <div class="max-w-2xl">
        <a href="{{ route('dashboard.services.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">
            &larr; Назад кон услуги
        </a>

        <div class="mt-4">
            <h1 class="text-2xl font-bold text-gray-900">
                Измени {{ $service->name }}
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Измени {{ $service->name }}.
            </p>
        </div>

        <form method="POST"
              action="{{ route('dashboard.services.update', $service) }}"
              class="card mt-8 space-y-5 p-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="label">Име на услуга</label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $service->name) }}"
                    required
                    class="input"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="label">
                    Опис
                    <span class="font-normal text-gray-400">(optional)</span>
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="input"
                >{{ old('description', $service->description) }}</textarea>

                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="duration_minutes" class="label">
                        Времетраење
                    </label>

                    <div class="relative">
                        <input
                            id="duration_minutes"
                            type="number"
                            name="duration_minutes"
                            value="{{ old('duration_minutes', $service->duration_minutes) }}"
                            min="1"
                            required
                            class="input pr-16"
                        >

                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">
                            мин
                        </span>
                    </div>

                    @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="label">
                        Price
                    </label>

                    <div class="relative">
                        <input
                            id="price"
                            type="number"
                            name="price"
                            value="{{ old('price', $service->price) }}"
                            min="0"
                            step="0.01"
                            required
                            class="input pr-16"
                        >

                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">
                            MKD
                        </span>
                    </div>

                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-gray-200 pt-5">
                <a href="{{ route('dashboard.services.index') }}"
                   class="text-sm font-medium text-gray-500 hover:text-gray-700">
                    Откажи
                </a>

                <button type="submit" class="btn-primary">
                 Зачувај промени
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>