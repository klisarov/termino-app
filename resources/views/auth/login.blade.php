<x-layouts.guest title="Log in">
    <h1 class="text-xl font-semibold text-gray-900">Најави се на Termino</h1>
    <p class="mt-1 text-sm text-gray-500">Управувај со твоите резервации.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="label">Password</label>
            <input id="password" type="password" name="password" required class="input">
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
            Remember me
        </label>

        <button type="submit" class="btn-primary w-full">Log in</button>

        <div class="flex items-center justify-between text-sm">
            <a href="{{ route('password.request') }}" class="text-brand-600 hover:text-brand-700">Заборавена лозинка?</a>
            <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700">Направи бизнис сметка</a>
        </div>
    </form>
</x-layouts.guest>