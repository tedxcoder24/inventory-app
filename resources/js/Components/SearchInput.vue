<script setup>
import { ref } from 'vue';
import { debounce } from 'lodash-es';
import { router } from '@inertiajs/vue3';

const searchQuery = ref('');
const performSearch = debounce(() => {
    router.get('/items', { query: searchQuery.value }, { preserveState: true, replace: true });
}, 300);
</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm min-w-80"
        v-model="searchQuery"
        type="search"
        @input="performSearch"
        placeholder="Search by serial number..."
    />
</template>
