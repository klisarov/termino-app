<x-layouts.guest title="Forgot password">
    <h1 class="text-xl font-semibold text-gray-900">Forgot your password?</h1>
    <p class="mt-1 text-sm text-gray-500">We'll email you a link to reset it.</p>

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-primary w-full">Email password reset link</button>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-700">Back to login</a>
        </p>
    </form>
</x-layouts.guest>