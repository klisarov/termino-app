@if (session('status'))
    <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-xl bg-green-50 px-4 py-3 text-sm font-medium text-green-800 ring-1 ring-inset ring-green-200">
            {{ session('status') }}
        </div>
    </div>
@endif

@if (session('booking_error'))
    <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-800 ring-1 ring-inset ring-red-200">
            {{ session('booking_error') }}
        </div>
    </div>
@endif