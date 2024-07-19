<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatsSection from './Partials/StatsSection.vue';
import axios from 'axios';

const props = defineProps({
    current_data: { type: Array },
    historical_stats: { type: Array },
    today_data: { type: Array },
    yesterday_data: { type: Array },
    current_week_data: { type: Array },
    previous_week_data: { type: Array },
    current_month_data: { type: Array },
    previous_month_data: { type: Array },
});

const currentData = ref(props.current_data);
const historicalStats = ref(props.historical_stats);
const todayData = ref(props.today_data);
const yesterdayData = ref(props.yesterday_data);
const currentWeekData = ref(props.current_week_data);
const previousWeekData = ref(props.previous_week_data);
const currentMonthData = ref(props.current_month_data);
const previousMonthData = ref(props.previous_month_data);

onMounted(() => {
    window.Echo.channel('item-channel')
        .listen('ItemCreated', (event) => {
            // Handle item creation, e.g., add item to currentData
            console.log('Item created event received', event);
            fetchData();
        })
        .listen('ItemUpdated', (event) => {
            // Handle item update, e.g., update item in currentData
            console.log('Item created event updated', event);
            fetchData();
            // const index = currentData.value.findIndex(item => item.id === event.item.id);
            // if (index !== -1) {
            //     currentData.value[index] = event.item;
            // }
        })
        .listen('ItemDeleted', (event) => {
            // Handle item deletion, e.g., remove item from currentData
            console.log('Item created event deleted', event);
            fetchData();
            // currentData.value = currentData.value.filter(item => item.id !== event.itemId);
        });
});

const fetchData = async () => {
    const response = await axios.get('/dashboard/data');
    const data = response.data;

    currentData.value = data.current_data;
    historicalStats.value = data.historical_stats;
    todayData.value = data.today_data;
    yesterdayData.value = data.yesterday_data;
    currentWeekData.value = data.current_week_data;
    previousWeekData.value = data.previous_week_data;
    currentMonthData.value = data.current_month_data;
    previousMonthData.value = data.previous_month_data;
}

function formatDate(date) {
    const options = { month: '2-digit', day: '2-digit', year: '2-digit' };
    return date.toLocaleDateString('en-US', options);
}

function getTodayDate() {
    const today = new Date();
    return formatDate(today);
}

function getYesterdayDate() {
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    return formatDate(yesterday);
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
                            <div class="flex gap-6">
                                <div class="flex flex-col w-1/2 bg-gray-100">
                                    <div class="flex justify-center items-center p-4 bg-emerald-200">
                                        <h2 class="font-semibold text-center text-xl text-gray-700 leading-tight"> CURRENT INVENTORY </h2>
                                    </div>

                                    <div v-if="currentData.length === 0">
                                        <div class="flex justify-center items-center p-8">
                                            <span class="font-semibold text-center text-lg"> No Data </span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <StatsSection :data="currentData" />
                                    </div>

                                    <div class="flex justify-center items-center p-4 bg-emerald-200">
                                        <h2 class="font-semibold text-center text-xl text-gray-700 leading-tight"> HISTORICAL STATS </h2>
                                    </div>

                                    <div v-if="historicalStats.length === 0">
                                        <div class="flex justify-center items-center p-8">
                                            <span class="font-semibold text-center text-lg"> No Data </span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <StatsSection :data="historicalStats" />
                                    </div>
                                </div>

                                <div class="flex flex-col w-1/2 bg-gray-100">
                                    <div class="flex justify-center items-center p-4 bg-orange-200">
                                        <h2 class="font-semibold text-center text-xl text-gray-700 leading-tight"> INVENTORY STATS </h2>
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Today: {{ getTodayDate() }} </h2>
                                        </div>

                                        <div v-if="todayData.length === 0">
                                            <div class="flex justify-center items-center p-8">
                                                <span class="font-semibold text-center text-lg"> No Data </span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <StatsSection :data="todayData" />
                                        </div>
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Yesterday: {{ getYesterdayDate() }} </h2>
                                        </div>

                                        <div v-if="yesterdayData.length === 0">
                                            <div class="flex justify-center items-center p-8">
                                                <span class="font-semibold text-center text-lg"> No Data </span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <StatsSection :data="yesterdayData" />
                                        </div>
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Current Week: {{ getCurrentWeekDates().start }} - {{ getCurrentWeekDates().end }} </h2>
                                        </div>

                                        <div v-if="currentWeekData.length === 0">
                                            <div class="flex justify-center items-center p-8">
                                                <span class="font-semibold text-center text-lg"> No Data </span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <StatsSection :data="currentWeekData" />
                                        </div>
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Previous Week: {{ getPreviousWeekDates().start }} - {{ getPreviousWeekDates().end }} </h2>
                                        </div>

                                        <div v-if="previousWeekData.length === 0">
                                            <div class="flex justify-center items-center p-8">
                                                <span class="font-semibold text-center text-lg"> No Data </span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <StatsSection :data="previousWeekData" />
                                        </div>
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Current Month: {{ getCurrentMonthDates().start }} - {{ getCurrentMonthDates().end }} </h2>
                                        </div>

                                        <div v-if="currentMonthData.length === 0">
                                            <div class="flex justify-center items-center p-8">
                                                <span class="font-semibold text-center text-lg"> No Data </span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <StatsSection :data="currentMonthData" />
                                        </div>
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-center items-center p-2 bg-red-100">
                                            <h2 class="text-center text-base text-gray-700 leading-tight"> Previous Month: {{ getPreviousMonthDates().start }} - {{ getPreviousMonthDates().end }} </h2>
                                        </div>

                                        <div v-if="previousMonthData.length === 0">
                                            <div class="flex justify-center items-center p-8">
                                                <span class="font-semibold text-center text-lg"> No Data </span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <StatsSection :data="previousMonthData" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
