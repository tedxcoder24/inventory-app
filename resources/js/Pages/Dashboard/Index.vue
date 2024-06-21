<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import StatsSection from './Partials/StatsSection.vue';

const props = defineProps({
    current_data: { type: Array },
    current_week_data: { type: Array },
    previous_week_data: { type: Array },
    current_month_data: { type: Array },
    previous_month_data: { type: Array },
    from_date: { type: Date },
    to_date: { type: Date }
});

function formatDate(date) {
    const options = { month: '2-digit', day: '2-digit', year: '2-digit' };
    return date.toLocaleDateString('en-US', options);
}

function getCurrentWeekDates() {
    const now = new Date();
    const dayOfWeek = now.getDay(); // 0 (Sunday) - 6 (Saturday)
    const firstDay = new Date(now);
    const lastDay = new Date(now);

    // Adjust to get the previous Monday
    const diffToFirstDay = (dayOfWeek === 0 ? 6 : dayOfWeek - 1); // shift days to get back to Monday
    firstDay.setDate(now.getDate() - diffToFirstDay);
    firstDay.setHours(0, 0, 0, 0);

    // Adjust to get the coming Sunday
    const diffToLastDay = (dayOfWeek === 0 ? 0 : 7 - dayOfWeek);
    lastDay.setDate(now.getDate() + diffToLastDay);
    lastDay.setHours(23, 59, 59, 999);

    return { start: formatDate(firstDay), end: formatDate(lastDay) };
}

const getPreviousWeekDates = () => {
    const now = new Date();
    const dayOfWeek = now.getDay(); // 0 (Sunday) - 6 (Saturday)
    const firstDayOfCurrentWeek = new Date(now);
    const diff = (dayOfWeek === 0 ? 6 : dayOfWeek - 1);
    firstDayOfCurrentWeek.setDate(now.getDate() - diff);

    const firstDayOfPreviousWeek = new Date(firstDayOfCurrentWeek);
    firstDayOfPreviousWeek.setDate(firstDayOfCurrentWeek.getDate() - 7);
    
    const lastDayOfPreviousWeek = new Date(firstDayOfPreviousWeek);
    lastDayOfPreviousWeek.setDate(firstDayOfPreviousWeek.getDate() + 6);

    // Set hours to the start of the day
    firstDayOfPreviousWeek.setHours(0, 0, 0, 0);
    lastDayOfPreviousWeek.setHours(23, 59, 59, 999);
    
    return { start: formatDate(firstDayOfPreviousWeek), end: formatDate(lastDayOfPreviousWeek) };
}

const getPreviousMonthDates = () => {
    const now = new Date();
    const firstDayOfCurrentMonth = new Date(now.getFullYear(), now.getMonth(), 1);

    const firstDayOfPreviousMonth = new Date(firstDayOfCurrentMonth);
    firstDayOfPreviousMonth.setMonth(firstDayOfCurrentMonth.getMonth() - 1);
    
    const lastDayOfPreviousMonth = new Date(firstDayOfPreviousMonth.getFullYear(), firstDayOfPreviousMonth.getMonth() + 1, 0);

    // Set hours to the start of the day
    firstDayOfPreviousMonth.setHours(0, 0, 0, 0);
    lastDayOfPreviousMonth.setHours(23, 59, 59, 999);
    
    return { start: formatDate(firstDayOfPreviousMonth), end: formatDate(lastDayOfPreviousMonth) };
}

const getCurrentMonthDates = () => {
    const now = new Date();
    const firstDayOfCurrentMonth = new Date(now.getFullYear(), now.getMonth(), 1);

    const firstDayOfPreviousMonth = new Date(firstDayOfCurrentMonth);
    firstDayOfPreviousMonth.setMonth(firstDayOfCurrentMonth.getMonth() - 1);
    
    const lastDayOfCurrentMonth = new Date(firstDayOfCurrentMonth.getFullYear(), firstDayOfCurrentMonth.getMonth() + 1, 0);

    // Set hours to the start of the day
    firstDayOfCurrentMonth.setHours(0, 0, 0, 0);
    lastDayOfCurrentMonth.setHours(23, 59, 59, 999);
    
    return { start: formatDate(firstDayOfCurrentMonth), end: formatDate(lastDayOfCurrentMonth) };
}

const form = useForm({
    from_date_time: props.from_date,
    to_date_time: props.to_date,
});

const submit = () => {
    form.get(route('dashboard'));
}

const viewPreviousWeek = () => {
    const previousWeek = getPreviousWeekDates();
    form.from_date_time = previousWeek.start;
    form.to_date_time = previousWeek.end;
    submit();
}

const viewPreviousMonth = () => {
    const previousMonth = getPreviousMonthDates();
    form.from_date_time = previousMonth.start;
    form.to_date_time = previousMonth.end;
    submit();
}

const viewCurrentWeek = () => {
    const currentWeek = getCurrentWeekDates();
    form.from_date_time = currentWeek.start;
    form.to_date_time = currentWeek.end;
    submit();
}

const getAttributeKeys = (item) => {
    if (item.statuses) {
        const statusKeys = Object.keys(item.statuses);
        if (statusKeys.length > 0) {
            const status = item.statuses[statusKeys[0]];
            return Object.keys(status).filter(key => key !== 'weight' && key !== 'count')[0];
        }
    }
    return []
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight"> Dashboard</h2>
        </template>

        <div class="py-6">
            <div class="mx-auto px-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col justify-center gap-8">
                            <div>
                                <h2 class="font-semibold text-center text-xl text-gray-700 leading-tight"> Inventory Stats </h2>
                            </div>

                            <div class="flex justify-between">
                                <form class="flex gap-6" @submit.prevent="submit">
                                    <div>
                                        <InputLabel for="from_date_time" value="From" />
    
                                        <DateTimePicker
                                            id="from_date_time"
                                            class="mt-1 block"
                                            required
                                            v-model="form.from_date_time"
                                        />
                                    </div>
    
                                    <div>
                                        <InputLabel for="to_date_time" value="To" />
    
                                        <DateTimePicker
                                            id="to_date_time"
                                            class="mt-1 block"
                                            required
                                            v-model="form.to_date_time"
                                        />
                                    </div>
    
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        View
                                    </PrimaryButton>
                                </form>

                                <div class="flex gap-6">
                                    <SecondaryButton @click="viewCurrentWeek">Current Week</SecondaryButton>
                                    <SecondaryButton @click="viewPreviousWeek">Previous Week</SecondaryButton>
                                    <SecondaryButton @click="viewPreviousMonth">Previous Month</SecondaryButton>
                                </div>
                            </div>

                            <div class="flex gap-6">
                                <div class="flex flex-col w-1/2 bg-gray-100">
                                    <div class="flex justify-center items-center p-4 bg-emerald-200">
                                        <h2 class="font-semibold text-center text-xl text-gray-700 leading-tight"> CURRENT INVENTORY </h2>
                                    </div>

                                    <StatsSection :data="current_data" />
                                </div>

                                <div class="flex flex-col w-1/2 bg-gray-100">
                                    <div class="flex justify-center items-center p-4 bg-orange-200">
                                        <h2 class="font-semibold text-center text-xl text-gray-700 leading-tight"> INVENTORY STATS </h2>
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Current Week: {{ getCurrentWeekDates().start }} - {{ getCurrentWeekDates().end }} </h2>
                                        </div>

                                        <StatsSection :data="current_week_data" />
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Previous Week: {{ getPreviousWeekDates().start }} - {{ getPreviousWeekDates().end }} </h2>
                                        </div>

                                        <StatsSection :data="previous_week_data" />
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Current Month: {{ getCurrentMonthDates().start }} - {{ getCurrentMonthDates().end }} </h2>
                                        </div>

                                        <StatsSection :data="current_month_data" />
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Previous Month: {{ getPreviousMonthDates().start }} - {{ getPreviousMonthDates().end }} </h2>
                                        </div>

                                        <StatsSection :data="previous_month_data" />
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="flex flex-wrap justify-around gap-6">
                                <div v-for="(item, id) in data" :key="id">
                                    <table class="block overflow-y-auto whitespace-nowrap divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-lg font-medium tracking-wider text-center text-gray-900 uppercase"> {{ item.product }} </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> grams </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> lbs </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> {{ getAttributeKeys(item) }} </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="(status, statusId) in item.statuses" :key="statusId">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ statusId }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status.weight }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ (status.weight * 0.00220642).toFixed(2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status[getAttributeKeys(item)] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
