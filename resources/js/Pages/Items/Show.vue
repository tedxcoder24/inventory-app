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
                        <p class="text-gray-900 mb-6"> Item Details - You can see the details of item and the status change history. </p>

                        <div class="flex justify-between gap-8">
                            <div class="w-full">
                                <div class="flex flex-col justify-between items-center gap-6">
                                    <div class="text-lg font-medium text-gray-900"> Operator: {{ item.operator.operator }} </div>
        
                                    <div class="text-lg font-medium text-gray-900"> Item Type: {{ item.item_type.item_type }} </div>
        
                                    <div class="text-lg font-medium text-gray-900"> Strain: {{ item.strain.strain }} </div>
    
                                    <div class="text-lg font-medium text-gray-900"> Product: {{ item.product.product }} </div>
        
                                    <div class="text-lg font-medium text-gray-900"> Color: {{ item.color.color }} </div>
        
                                    <div class="text-lg font-medium text-gray-900"> Clarity: {{ item.clarity.clarity }} </div>
        
                                    <div class="text-lg font-medium text-gray-900"> Appearance: {{ item.appearance.appearance }} </div>
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
                                                    Note
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="status in item.statuses" :key="status.id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status.operator['operator'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td><td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status.date_time }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td><td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status.status.status }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td><td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status.note }}
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
