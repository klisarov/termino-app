<x-layouts.public>
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 text-center sm:px-6 sm:py-24 lg:px-8">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
               Најди ја услугата која ти е потребна
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-lg text-gray-500">
                Истражи бизниси во Македонија и закажи термин во секунди — без никаков account.
            </p>

            <form action="{{ route('marketplace.index') }}" method="GET" class="mx-auto mt-10 max-w-2xl">
                <div class="flex flex-col gap-3 rounded-2xl bg-white p-3 shadow-lg ring-1 ring-gray-200 sm:flex-row sm:items-center">
                    <div class="flex flex-1 items-center gap-2 px-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <input type="text" name="q" placeholder="Што бараш?" class="w-full border-0 p-2 text-sm focus:ring-0">
                    </div>
                    <div class="hidden h-8 w-px bg-gray-200 sm:block"></div>
                    <div class="flex flex-1 items-center gap-2 px-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <input type="text" name="city" placeholder="Каде? пр. Скопје" class="w-full border-0 p-2 text-sm focus:ring-0">
                    </div>
                    <button type="submit" class="btn-primary shrink-0">Пребарувај</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h2 class="text-lg font-semibold text-gray-900">Пребарувај преку категорија</h2>
        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($categories as $category)
                <a href="{{ route('marketplace.index', ['category' => $category->slug]) }}"
                   class="card flex flex-col items-center gap-2 p-5 text-center transition hover:shadow-md hover:-translate-y-0.5">
                    <span class="text-sm font-medium text-gray-800">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    @if ($featuredTenants->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Ново регистрирани бизниси</h2>
                <a href="{{ route('marketplace.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Види се &rarr;</a>
            </div>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredTenants as $tenant)
                    <x-business-card :tenant="$tenant" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="card flex flex-col items-center gap-4 bg-gray-900 p-10 text-center text-white sm:flex-row sm:justify-between sm:text-left">
                <div>
                    <h2 class="text-2xl font-bold">Овозможи им на твоите услуги да бидат закажани и без повици.</h2>
                    <p class="mt-2 text-gray-300">Подеси го твојот бизнис, услуги и достапност брзо и едноставно.</p>
                </div>
                <a href="{{ route('register') }}" class="btn-primary shrink-0 !bg-white !text-gray-900 hover:!bg-gray-100">Закажи демо</a>
            </div>
        </div>
    </section>
</x-layouts.public>