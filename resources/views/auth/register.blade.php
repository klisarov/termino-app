<x-layouts.guest title="Create your business">
    <h1 class="text-xl font-semibold text-gray-900">Креирај страна за твојот бизнис</h1>
    <p class="mt-1 text-sm text-gray-500">Земи ја својата страна веднаш!</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="business_name" class="label">Име на бизнис</label>
            <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" required autofocus class="input">
            @error('business_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="owner_name" class="label">Вашето име</label>
            <input id="owner_name" type="text" name="owner_name" value="{{ old('owner_name') }}" required class="input">
            @error('owner_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="category_id" class="label">Категорија</label>
                <select id="category_id" name="category_id" required class="input">
                    <option value="">Select...</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="city" class="label">Град</label>
                <input id="city" type="text" name="city" value="{{ old('city') }}" required class="input">
                @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="input">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="password" class="label">Password</label>
                <input id="password" type="password" name="password" required class="input">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="label">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="input">
            </div>
        </div>

        <button type="submit" class="btn-primary w-full">Креирај бизнис профил</button>

        <p class="text-center text-sm text-gray-500">
            Имаш профил? <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-700">Log in</a>
        </p>
    </form>
</x-layouts.guest>