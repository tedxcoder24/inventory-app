<script setup>
defineProps({
    data: { type: Array },
});
</script>

<template>
    <section>
        <div class="p-1">
            <div class="flex flex-col gap-6">
                <div v-for="(item, dataId) in data" :key="dataId">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight"> {{ item.itemType }} </h2>

                    <div class="flex flex-wrap justify-around gap-4 m-4">
                        <div v-for="(product, productId) in item.products" :key="productId">
                            <table class="block overflow-y-auto whitespace-nowrap border border-gray-200 w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-lg font-medium tracking-wider text-center text-gray-900 uppercase border-r border-b border-gray-200 bg-yellow-200"> {{ product.product }} </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-r border-b border-gray-200 bg-indigo-200"> grams </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-r border-b border-gray-200 bg-indigo-200"> lbs </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-r border-b border-gray-200 bg-indigo-200"> {{ item.itemType }} </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr v-for="(status, statusId) in product.statuses" :key="statusId">
                                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200 bg-orange-200">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ status.status }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ status.weight }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
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
                                                        {{ status.count }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr class="border border-gray-400" />
                </div>
            </div>
        </div>
    </section>
</template>