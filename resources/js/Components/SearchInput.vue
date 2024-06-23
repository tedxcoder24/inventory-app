<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { debounce } from 'lodash-es';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    items: {
        type: Array,
        default: []
    },
});

const emit = defineEmits(['update:selectedItems']);

const options = ref([]);
const selectedItems = ref([]);
const showDropdown = ref(false);
const searchQuery = ref('');
const dropdownRef = ref(null);

const performSearch = debounce(() => {
    router.get('/items', { query: searchQuery.value }, { preserveState: true, replace: true });
}, 300);

const updateItems = () => {
    if (props.items) {
        options.value = props.items;
    }
}

watch(() => props.items, updateItems, { immediate: true });

const filteredOptions = computed(() => {
    return options.value.filter(option => {
        return !selectedItems.value.some(item => item.id === option.id)
    });
});

const selectOption = (option) => {
    selectedItems.value.push(option);
    searchQuery.value = '';
    showDropdown.value = false;
    emit('update:selectedItems', selectedItems.value);
};

const removeItem = (id) => {
    selectedItems.value = selectedItems.value.filter(item => item.id !== id);
    emit('update:selectedItems', selectedItems.value);
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        showDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

const handleKeydown = (event) => {
    if (showDropdown.value && filteredOptions.value.length > 0) {
        if (event.key === 'Enter') {
            selectOption(filteredOptions.value[0]);
        }
    }
}
</script>

<template>
    <div ref="dropdownRef" class="relative flex">
        <div class="mt-1">
            <span v-for="item in selectedItems" :key="item.id" class="px-2 py-1 mr-2 inline-block bg-gray-400 rounded-2xl">
                {{ item.serial_number }} 
                <button class="bg-none cursor-pointer" @click="removeItem(item.id)">x</button>
            </span>
        </div>

        <div>
            <input
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm min-w-80"
                v-model="searchQuery"
                type="text"
                @input="performSearch"
                @focus="showDropdown = true"
                @keydown="handleKeydown"
                placeholder="Search by serial number..."
            />
            <div v-if="showDropdown && filteredOptions.length" class="border border-gray-300 rounded-md min-w-80 max-h-52 overflow-y-auto absolute bg-white">
                <ul class="list-none p-0 m-0">
                    <li
                        v-for="option in filteredOptions" 
                        class="p-2 cursor-pointer hover:bg-slate-400" 
                        :key="option.id" 
                        @click="selectOption(option)"
                    >
                        {{ option.serial_number }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
