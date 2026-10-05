<x-layouts.dashboard title="Add service">
    <div class="max-w-2xl">
        <a href="{{ route('dashboard.services.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">
            &larr; Back to services
        </a>

        <div class="mt-4">
            <h1 class="text-2xl font-bold text-gray-900">
                Add service
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Add a service that customers can book.
            </p>
        </div>

        <form method="POST"
              action="{{ route('dashboard.services.store') }}"
              class="card mt-8 space-y-5 p-6">
            @csrf

            <div>
                <label for="name" class="label">Service name</label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="input"
                    placeholder="e.g. Haircut"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="label">
                    Description
                    <span class="font-normal text-gray-400">(optional)</span>
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="input"
                    placeholder="Describe the service..."
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="duration_minutes" class="label">
                        Duration
                    </label>

                    <div class="relative">
                        <input
                            id="duration_minutes"
                            type="number"
                            name="duration_minutes"
                            value="{{ old('duration_minutes') }}"
                            min="1"
                            required
                            class="input pr-16"
                            placeholder="60"
                        >

                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">
                            min
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
                            value="{{ old('price') }}"
                            min="0"
                            step="0.01"
                            required
                            class="input pr-16"
                            placeholder="1500"
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
                    Cancel
                </a>

                <button type="submit" class="btn-primary">
                    Create service
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>