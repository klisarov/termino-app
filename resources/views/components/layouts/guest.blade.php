@props(['title' => null])

<x-layouts.base :title="$title">
    <div class="flex min-h-full flex-col items-center justify-center bg-gray-50 px-4 py-12">
        <a href="{{ route('home') }}" class="mb-8 flex items-center gap-2 text-xl font-bold text-gray-900">
            Termino
        </a>

        <div class="w-full max-w-md">
            <div class="card p-8">
                <x-ui.flash />
                {{ $slot }}
            </div>
        </div>
    </div>
</x-layouts.base>