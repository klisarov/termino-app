<x-layouts.guest title="Reset password">
    <h1 class="text-xl font-semibold text-gray-900">Reset your password</h1>

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus class="input">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="label">New password</label>
            <input id="password" type="password" name="password" required class="input">
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="label">Confirm new password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="input">
        </div>

        <button type="submit" class="btn-primary w-full">Reset password</button>
    </form>
</x-layouts.guest>