<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    item: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Inventory" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="flex justify-between items-center text-xl font-semibold leading-tight text-gray-800">
                <p>
                    Item
                    <i class="fa-solid fa-user-gear"></i>
                </p>
                <Link
                    :href="`/items/${item.id}/edit`"
                    class="px-4 py-2 mr-3 text-sm text-green-600 transition border border-green-300 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent hover:cursor-pointer"
                >
                    Edit
                </Link>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-900 mb-6"> Item Details - History of Item change </p>

                        <div class="flex justify-between gap-8">
                            <div class="w-full">
                                <div class="flex flex-col justify-between items-center gap-6">
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Serial Number: </label> 
                                            {{ item.serial_number }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Date and Time: </label>
                                            {{ item.date_time }} 
                                        </div>
                                    </div>
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Operator: </label>
                                            {{ item.operator.operator }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Item Type: </label>
                                            {{ item.item_type.item_type }} 
                                        </div>
                                    </div>
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Batch ID: </label>
                                            {{ item.batch_id }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Metric ID: </label>
                                            {{ item.metrc_id }} 
                                        </div>
                                    </div>
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Strain: </label>
                                            {{ item.strain.strain }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Product: </label>
                                            {{ item.product.product }} 
                                        </div>
                                    </div>
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Tare Weight: </label>
                                            {{ item.tare_weight }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Gross Weight: </label>
                                            {{ item.gross_weight }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Current Net Weight: </label>
                                            {{ item.change_histories[item.change_histories.length - 1].gross_weight - item.tare_weight }} 
                                        </div>
                                    </div>
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Color: </label>
                                            {{ item.color.color }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Clarity: </label>
                                            {{ item.clarity.clarity }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Appearance: </label>
                                            {{ item.appearance.appearance }} 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full">
                                <div class="flex flex-col gap-6">
                                    <table class="block overflow-y-auto whitespace-nowrap divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Operator
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Date Time
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Status
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Gross Weight
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Note
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="(history, id) in item.change_histories" :key="id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ history.operator.operator }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ history.date_time }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ history.status.status }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ history.gross_weight }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ history.note }}
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
