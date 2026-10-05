<x-layouts.public :title="'Book ' . $tenant->name">

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Back --}}
        <a
            href="{{ route('tenants.show', $tenant->slug) }}"
            class="text-sm text-gray-500 hover:text-gray-700"
        >
            &larr; {{ $tenant->name }}
        </a>

        {{-- Selected service --}}
        <div class="card mt-4 flex items-center justify-between gap-4 p-5">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-brand-600">
                    Избрана услуга
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $service->name }}
                </p>

                <p class="text-sm text-gray-500">
                    {{ $service->duration_minutes }} мин
                </p>
            </div>

            <p class="text-lg font-bold text-gray-900">
                {{ number_format($service->price, 0) }} MKD
            </p>

        </div>

        {{-- Validation errors --}}
        @error('start_time')
            <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-inset ring-red-200">
                {{ $message }}
            </div>
        @enderror

        @error('booking_date')
            <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-inset ring-red-200">
                {{ $message }}
            </div>
        @enderror

        {{-- Booking widget --}}
        <div
            x-data="bookingWidget({
                tenantSlug: @js($tenant->slug),
                serviceId: {{ $service->id }},
                initialDate: @js(old('booking_date')),
                initialSlot: @js(old('start_time')),
                datesUrl: @js(route('tenants.book.dates', $tenant->slug)),
                slotsUrl: @js(route('tenants.book.slots', $tenant->slug)),
            })"
            x-init="init()"
            class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2"
        >

            {{-- Calendar --}}
            <div class="card p-5">

                <div class="flex items-center justify-between">

                    <button
                        type="button"
                        @click="prevMonth()"
                        class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Предходен месец"
                    >
                        &larr;
                    </button>

                    <p
                        class="font-semibold text-gray-900"
                        x-text="monthLabel"
                    ></p>

                    <button
                        type="button"
                        @click="nextMonth()"
                        class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Следен месец"
                    >
                        &rarr;
                    </button>

                </div>

                {{-- Weekdays --}}
                <div class="mt-4 grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-400">
                    <span>Пон</span>
                    <span>Вто</span>
                    <span>Сре</span>
                    <span>Чет</span>
                    <span>Пет</span>
                    <span>Саб</span>
                    <span>Нед</span>
                </div>

                {{-- Calendar days --}}
                <div class="mt-1 grid grid-cols-7 gap-1">

                    <template
                        x-for="(cell, index) in calendarDays"
                        :key="index"
                    >

                        <div class="flex justify-center">

                            {{-- Empty cell --}}
                            <template x-if="cell === null">
                                <span class="h-9 w-9"></span>
                            </template>

                            {{-- Actual day --}}
                            <template x-if="cell !== null">

                                <button
                                    type="button"
                                    @click="selectDate(cell.iso)"
                                    :disabled="!isAvailable(cell.iso)"
                                    :class="{
                                        'bg-brand-600 text-white': selectedDate === cell.iso,
                                        'text-gray-700 hover:bg-brand-50': isAvailable(cell.iso) && selectedDate !== cell.iso,
                                        'cursor-not-allowed text-gray-300': !isAvailable(cell.iso)
                                    }"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg text-sm transition"
                                    x-text="cell.day"
                                ></button>

                            </template>

                        </div>

                    </template>

                </div>

                {{-- Loading --}}
                <p
                    x-show="loadingDates"
                    x-cloak
                    class="mt-3 text-xs text-gray-400"
                >
                Вчитувам достапност..&hellip;
                </p>

                {{-- Error --}}
                <p
                    x-show="datesError"
                    x-cloak
                    class="mt-3 text-xs text-red-500"
                    x-text="datesError"
                ></p>

                {{-- Empty --}}
                <p
                    x-show="!loadingDates && !datesError && availableDates.length === 0"
                    x-cloak
                    class="mt-3 text-xs text-gray-400"
                >
                    Нема достапност за овој месец.
                </p>

            </div>

            {{-- Available times --}}
            <div class="card p-5">

                <p class="font-semibold text-gray-900">
                    Достапни термини
                </p>

                <p
                    x-show="selectedDate"
                    x-cloak
                    class="mt-1 text-sm text-gray-500"
                    x-text="formattedSelectedDate()"
                ></p>

                <p
                    x-show="!selectedDate"
                    class="mt-1 text-sm text-gray-400"
                >
                    Избери датум.
                </p>

                {{-- Slots --}}
                <div
                    class="mt-4 grid grid-cols-3 gap-2"
                    x-show="selectedDate"
                    x-cloak
                >

                    <template
                        x-for="slot in slots"
                        :key="slot"
                    >

                        <button
                            type="button"
                            @click="selectSlot(slot)"
                            :class="
                                selectedSlot === slot
                                    ? 'bg-brand-600 text-white'
                                    : 'bg-gray-50 text-gray-700 hover:bg-brand-50'
                            "
                            class="rounded-lg px-2 py-2 text-sm font-medium transition"
                            x-text="slot"
                        ></button>

                    </template>

                </div>

                {{-- Loading slots --}}
                <p
                    x-show="loadingSlots"
                    x-cloak
                    class="mt-3 text-xs text-gray-400"
                >
                    Вчитувам времиња..&hellip;
                </p>

                {{-- Slot error --}}
                <p
                    x-show="slotsError"
                    x-cloak
                    class="mt-3 text-xs text-red-500"
                    x-text="slotsError"
                ></p>

                {{-- No slots --}}
                <p
                    x-show="selectedDate && !loadingSlots && !slotsError && slots.length === 0"
                    x-cloak
                    class="mt-3 text-xs text-gray-400"
                >
                    Нема достапни часови за овој датум — пробај друг датум.
                </p>

            </div>

            {{-- Customer form --}}
            <form
                method="POST"
                action="{{ route('tenants.book.store', $tenant->slug) }}"
                class="card space-y-4 p-5 md:col-span-2"
                x-show="selectedSlot"
                x-cloak
            >

                @csrf

                <input
                    type="hidden"
                    name="service_id"
                    value="{{ $service->id }}"
                >

                <input
                    type="hidden"
                    name="booking_date"
                    :value="selectedDate"
                >

                <input
                    type="hidden"
                    name="start_time"
                    :value="selectedSlot"
                >

                <div>
                    <p class="font-semibold text-gray-900">
                        Информации за клиентот
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Внеси твои информации за да ја потврдиш резервацијата.
                    </p>
                </div>

                {{-- Name + phone --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <label class="label">
                            Име и презиме
                        </label>

                        <input
                            type="text"
                            name="customer_name"
                            value="{{ old('customer_name') }}"
                            required
                            class="input"
                        >

                        @error('customer_name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="label">
                            Телефонски број
                        </label>

                        <input
                            type="text"
                            name="customer_phone"
                            value="{{ old('customer_phone') }}"
                            required
                            class="input"
                        >

                        @error('customer_phone')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Email --}}
                <div>

                    <label class="label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="customer_email"
                        value="{{ old('customer_email') }}"
                        required
                        class="input"
                    >

                    @error('customer_email')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Address + city --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>

                        <label class="label">
                            Адреса
                            <span class="text-gray-400">(optional)</span>
                        </label>

                        <input
                            type="text"
                            name="customer_address"
                            value="{{ old('customer_address') }}"
                            class="input"
                        >

                    </div>

                    <div>

                        <label class="label">
                            Град
                            <span class="text-gray-400">(optional)</span>
                        </label>

                        <input
                            type="text"
                            name="customer_city"
                            value="{{ old('customer_city') }}"
                            class="input"
                        >

                    </div>

                </div>

                {{-- Note --}}
                <div>

                    <label class="label">
                        Note
                        <span class="text-gray-400">(optional)</span>
                    </label>

                    <textarea
                        name="customer_note"
                        rows="2"
                        class="input"
                    >{{ old('customer_note') }}</textarea>

                </div>

                {{-- Confirm --}}
                <button
                    type="submit"
                    class="btn-primary w-full"
                >
                    Потврди резервација
                </button>

            </form>

        </div>

    </div>

    @push('scripts')

        <script>
            window.bookingWidget = function ({
                tenantSlug,
                serviceId,
                initialDate,
                initialSlot,
                datesUrl,
                slotsUrl,
            }) {
                return {

                    tenantSlug,
                    serviceId,

                    datesUrl,
                    slotsUrl,

                    viewYear: new Date().getFullYear(),
                    viewMonth: new Date().getMonth(),

                    availableDates: [],

                    selectedDate: initialDate || null,

                    slots: [],

                    selectedSlot: initialSlot || null,

                    loadingDates: false,
                    loadingSlots: false,

                    datesError: null,
                    slotsError: null,

                    init() {

                        /*
                         * If an old booking date exists after validation
                         * failure, open that month automatically.
                         */
                        if (this.selectedDate) {

                            const date = new Date(
                                this.selectedDate + 'T00:00:00'
                            );

                            if (!Number.isNaN(date.getTime())) {
                                this.viewYear = date.getFullYear();
                                this.viewMonth = date.getMonth();
                            }
                        }

                        this.fetchDates();

                        if (this.selectedDate) {
                            this.fetchSlots();
                        }
                    },

                    get monthLabel() {

                        return new Date(
                            this.viewYear,
                            this.viewMonth,
                            1
                        ).toLocaleDateString('en-GB', {
                            month: 'long',
                            year: 'numeric',
                        });

                    },

                    get calendarDays() {

                        const firstOfMonth = new Date(
                            this.viewYear,
                            this.viewMonth,
                            1
                        );

                        /*
                         * JavaScript:
                         * Sunday = 0
                         * Monday = 1
                         *
                         * Convert to Monday-first:
                         * Monday = 0
                         * Sunday = 6
                         */
                        const startOffset =
                            (firstOfMonth.getDay() + 6) % 7;

                        const daysInMonth = new Date(
                            this.viewYear,
                            this.viewMonth + 1,
                            0
                        ).getDate();

                        const days = [];

                        for (
                            let i = 0;
                            i < startOffset;
                            i++
                        ) {
                            days.push(null);
                        }

                        for (
                            let day = 1;
                            day <= daysInMonth;
                            day++
                        ) {

                            const month = String(
                                this.viewMonth + 1
                            ).padStart(2, '0');

                            const dayString = String(day).padStart(
                                2,
                                '0'
                            );

                            days.push({
                                day,
                                iso: `${this.viewYear}-${month}-${dayString}`,
                            });
                        }

                        return days;
                    },

                    isAvailable(iso) {
                        return this.availableDates.includes(iso);
                    },

                    prevMonth() {

                        this.viewMonth--;

                        if (this.viewMonth < 0) {
                            this.viewMonth = 11;
                            this.viewYear--;
                        }

                        this.selectedDate = null;
                        this.selectedSlot = null;
                        this.slots = [];

                        this.fetchDates();
                    },

                    nextMonth() {

                        this.viewMonth++;

                        if (this.viewMonth > 11) {
                            this.viewMonth = 0;
                            this.viewYear++;
                        }

                        this.selectedDate = null;
                        this.selectedSlot = null;
                        this.slots = [];

                        this.fetchDates();
                    },

                    async fetchDates() {

                        this.loadingDates = true;
                        this.datesError = null;

                        try {

                            const url = new URL(
                                this.datesUrl,
                                window.location.origin
                            );

                            url.searchParams.set(
                                'service_id',
                                this.serviceId
                            );

                            const response = await fetch(
                                url.toString(),
                                {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                }
                            );

                            if (!response.ok) {
                                throw new Error(
                                    `HTTP ${response.status}`
                                );
                            }

                            const data = await response.json();

                            const monthStr =
                                `${this.viewYear}-${String(
                                    this.viewMonth + 1
                                ).padStart(2, '0')}`;

                            this.availableDates =
                                Array.isArray(data.dates)
                                    ? data.dates.filter(date =>
                                        date.startsWith(monthStr)
                                    )
                                    : [];

                        } catch (error) {

                            console.error(
                                'Failed to load available dates:',
                                error
                            );

                            this.availableDates = [];

                            this.datesError =
                                'Unable to load availability. Please try again.';

                        } finally {

                            this.loadingDates = false;
                        }
                    },

                    selectDate(iso) {

                        if (!this.isAvailable(iso)) {
                            return;
                        }

                        this.selectedDate = iso;
                        this.selectedSlot = null;
                        this.slots = [];

                        this.fetchSlots();
                    },

                    async fetchSlots() {

                        if (!this.selectedDate) {
                            return;
                        }

                        this.loadingSlots = true;
                        this.slotsError = null;
                        this.slots = [];

                        try {

                            const url = new URL(
                                this.slotsUrl,
                                window.location.origin
                            );

                            url.searchParams.set(
                                'service_id',
                                this.serviceId
                            );

                            url.searchParams.set(
                                'date',
                                this.selectedDate
                            );

                            const response = await fetch(
                                url.toString(),
                                {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                }
                            );

                            if (!response.ok) {
                                throw new Error(
                                    `HTTP ${response.status}`
                                );
                            }

                            const data = await response.json();

                            this.slots =
                                Array.isArray(data.slots)
                                    ? data.slots
                                    : [];

                        } catch (error) {

                            console.error(
                                'Failed to load available slots:',
                                error
                            );

                            this.slots = [];

                            this.slotsError =
                                'Unable to load available times. Please try again.';

                        } finally {

                            this.loadingSlots = false;
                        }
                    },

                    selectSlot(slot) {
                        this.selectedSlot = slot;
                    },

                    formattedSelectedDate() {

                        if (!this.selectedDate) {
                            return '';
                        }

                        return new Date(
                            this.selectedDate + 'T00:00:00'
                        ).toLocaleDateString('en-GB', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                        });
                    },

                };
            };
        </script>

    @endpush

</x-layouts.public>