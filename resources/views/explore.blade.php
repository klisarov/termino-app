<x-layouts.public title="Explore">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
            <aside class="lg:col-span-1">
                <form action="{{ route('marketplace.index') }}" method="GET" class="card space-y-5 p-5">
                    <div>
                        <label class="label">Пребарувај</label>
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Име на услуга или бизнис"
                               class="input">
                    </div>

                    <div>
                        <label class="label">Град</label>
                        <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="e.g. Skopje" class="input">
                    </div>

                    <div>
                        <label class="label">Категорија</label>
                        <select name="category" class="input">
                            <option value="">Сите категории</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected(($filters['category'] ?? null) === $category->slug)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label">Препорачана цена (MKD)</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min" class="input">
                            <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max" class="input">
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="available_today" value="1" @checked(!empty($filters['available_today']))
                               class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        Достапно денес
                    </label>

                    <button type="submit" class="btn-primary w-full">Примени филтри</button>
                    <a href="{{ route('marketplace.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700">Чисти филтри</a>
                </form>
            </aside>

            <div class="lg:col-span-3">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-900">
                        {{ $tenants->total() }} {{ Str::plural('business', $tenants->total()) }} found
                    </h1>
                </div>

                @if ($tenants->isEmpty())
                    <div class="card p-12 text-center text-gray-500">
                        Нема бизниси кои се соодветни за вашето пребарување. Пробајте со поопширни филтри.
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($tenants as $tenant)
                            <x-business-card :tenant="$tenant" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $tenants->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.public>