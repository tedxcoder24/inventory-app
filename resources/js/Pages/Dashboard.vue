<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    items: {
        type: Object,
        required: true,
    },
    products: {
        type: Array,
        required: true,
    },
    currentItems: {
        type: Array,
        required: true,
    },
    inventories: {
        type: Array,
    },
});

function getFirstDateOfCurrentWeek() {
    const now = new Date();
    const dayOfWeek = now.getDay(); // 0 (Sunday) - 6 (Saturday)
    const firstDay = new Date(now);
    
    // Adjust to get the previous Monday
    const diff = (dayOfWeek === 0 ? 6 : dayOfWeek - 1); // shift days to get back to Monday
    firstDay.setDate(now.getDate() - diff);
    
    // Set hours to the start of the day
    firstDay.setHours(0, 0, 0, 0);
    
    return firstDay;
}

const firstDayOfWeek = getFirstDateOfCurrentWeek();

const form = useForm({
    from_date_time: firstDayOfWeek,
    to_date_time: new Date(),
});

const submit = () => {
    form.get(route('dashboard'));
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

                            <div class="flex justify-around gap-6">
                                <div v-for="(data, productId) in inventories" :key="productId">
                                    <table class="block overflow-y-auto whitespace-nowrap divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-lg font-medium tracking-wider text-center text-gray-900 uppercase"> {{ productId }} </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> grams </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> kilograms </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> pounds </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> ounce </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="(statusData, statusId) in data.statuses" :key="statusId">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ statusData.status.status }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ statusData.totalWeight.g }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ statusData.totalWeight.kg }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ statusData.totalWeight.lbs }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ statusData.totalWeight.oz }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
