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

const form = useForm({
    from_date_time: new Date(),
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
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> {{ data.product.product }} </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> Grams </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> Lbs </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"> Jars </th>
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
                                                                {{ statusData.totalWeight.grams }}
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
                                                                {{ statusData.totalWeight.jars }}
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
