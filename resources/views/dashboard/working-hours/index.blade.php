<x-layouts.dashboard title="Working hours">

    <div class="mx-auto max-w-4xl">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Работно време
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Подеси го твоето работно време.
            </p>
        </div>

        {{-- Status --}}
        @if (session('status'))
            <div class="mt-6 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-700 ring-1 ring-inset ring-green-200">
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="mt-6 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-inset ring-red-200">
                <p class="font-medium">Поправи го следното:</p>

                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('dashboard.working-hours.update') }}"
            class="mt-8"
        >
            @csrf
            @method('PUT')

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">

                @php
                    $days = [
                        1 => 'Понеделник',
                        2 => 'Вторник',
                        3 => 'Среда',
                        4 => 'Четврток',
                        5 => 'Петок',
                        6 => 'Сабота',
                        0 => 'Недела',
                    ];
                @endphp

                @foreach ($days as $dayNumber => $dayName)

                    @php
                        $workingHour = $workingHours->get($dayNumber);
                        $dayBreaks = $breaks->get($dayNumber, collect());
                        $break = $dayBreaks->first();

                        $isActive = $workingHour?->is_active ?? false;

                        $startTime = $workingHour?->start_time
                            ? substr($workingHour->start_time, 0, 5)
                            : '09:00';

                        $endTime = $workingHour?->end_time
                            ? substr($workingHour->end_time, 0, 5)
                            : '17:00';

                        $breakStart = $break?->start_time
                            ? substr($break->start_time, 0, 5)
                            : '';

                        $breakEnd = $break?->end_time
                            ? substr($break->end_time, 0, 5)
                            : '';
                    @endphp

                    <div
                        x-data="{
                            active: {{ $isActive ? 'true' : 'false' }}
                        }"
                        class="border-b border-gray-100 last:border-b-0"
                    >

                        {{-- Day row --}}
                        <div class="p-5 sm:p-6">

                            <div class="flex flex-col gap-5 sm:flex-row sm:items-start">

                                {{-- Day + toggle --}}
                                <div class="flex min-w-[150px] items-center justify-between sm:block">

                                    <p class="font-semibold text-gray-900">
                                        {{ $dayName }}
                                    </p>

                                    <label class="relative inline-flex cursor-pointer items-center sm:mt-3">
                                        <input
                                            type="checkbox"
                                            name="days[{{ $dayNumber }}][is_active]"
                                            value="1"
                                            x-model="active"
                                            class="peer sr-only"
                                        >

                                        <div class="h-6 w-11 rounded-full bg-gray-200 transition peer-checked:bg-brand-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-200">
                                            <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition peer-checked:translate-x-5"></div>
                                        </div>

                                        <span class="ml-2 text-xs font-medium text-gray-500">
                                            <span x-text="active ? 'Отворено' : 'Затворено'"></span>
                                        </span>
                                    </label>

                                </div>

                                {{-- Hours --}}
                                <div
                                    x-show="active"
                                    x-cloak
                                    class="flex-1"
                                >

                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                        <div>
                                            <label class="label">
                                                Почеток на работа
                                            </label>

                                            <input
                                                type="time"
                                                name="days[{{ $dayNumber }}][start_time]"
                                                value="{{ old("days.$dayNumber.start_time", $startTime) }}"
                                                class="input"
                                                :disabled="!active"
                                            >
                                        </div>

                                        <div>
                                            <label class="label">
                                                Крај на работа
                                            </label>

                                            <input
                                                type="time"
                                                name="days[{{ $dayNumber }}][end_time]"
                                                value="{{ old("days.$dayNumber.end_time", $endTime) }}"
                                                class="input"
                                                :disabled="!active"
                                            >
                                        </div>

                                    </div>

                                    {{-- Break --}}
                                    <div class="mt-4 rounded-xl bg-gray-50 p-4">

                                        <div class="mb-3">
                                            <p class="text-sm font-medium text-gray-700">
                                                Пауза
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-400">
                                                Време за пауза во текот на работното време.
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                            <div>
                                                <label class="label">
                                                    Паузата започнува
                                                </label>

                                                <input
                                                    type="time"
                                                    name="days[{{ $dayNumber }}][break_start]"
                                                    value="{{ old("days.$dayNumber.break_start", $breakStart) }}"
                                                    class="input"
                                                    :disabled="!active"
                                                >
                                            </div>

                                            <div>
                                                <label class="label">
                                                    Паузата завршува
                                                </label>

                                                <input
                                                    type="time"
                                                    name="days[{{ $dayNumber }}][break_end]"
                                                    value="{{ old("days.$dayNumber.break_end", $breakEnd) }}"
                                                    class="input"
                                                    :disabled="!active"
                                                >
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                {{-- Closed state --}}
                                <div
                                    x-show="!active"
                                    x-cloak
                                    class="flex flex-1 items-center"
                                >
                                    <span class="text-sm text-gray-400">
                                        Затворено
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            {{-- Save --}}
            <div class="mt-6 flex justify-end">

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Зачувај работно време
                </button>

            </div>

        </form>

    </div>

</x-layouts.dashboard>