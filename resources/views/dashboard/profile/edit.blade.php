<x-layouts.dashboard title="Profile">

    <div class="max-w-2xl">

        <h1 class="text-2xl font-bold text-gray-900">
            Profile
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Управување со профилот на вашиот корисник и бизнис.
        </p>

        <form
            method="POST"
            action="{{ route('dashboard.profile.update') }}"
            class="card mt-8 space-y-5 p-6"
        >
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="label">
                    Име
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', auth()->user()->name) }}"
                    required
                    class="input"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="label">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', auth()->user()->email) }}"
                    required
                    class="input"
                >

                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-5">
                <button type="submit" class="btn-primary">
                    Зачувај промени
                </button>
            </div>

        </form>

    </div>

</x-layouts.dashboard>