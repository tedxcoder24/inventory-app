<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    header: {
        type: String,
        required: true,
    },
    attributes: {
        type: Array,
        required: true,
    },
});

const updateAttribute = (attr) => {
    router.put(route('attributes.update', attr.value), {
        enabled: attr.enabled ? false : true,
        attribute: props.header,
    });
}
</script>

<template>
    <table class="block overflow-y-auto whitespace-nowrap divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                    No
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                    {{ header }}
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                    Enabled
                </th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Action</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(attr, id) in attributes" :key="id">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ id + 1 }}
                            </div>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ attr.text }}
                            </div>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ attr.enabled ? 'Yes' : 'No' }}
                            </div>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <a
                            href="#"
                            @click="updateAttribute(attr)"
                            class="px-4 py-2 mr-3 text-sm text-green-600 transition border border-green-300 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent hover:cursor-pointer"
                        >
                            {{ attr.enabled ? 'Disable' : 'Enable' }}
                        </a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
