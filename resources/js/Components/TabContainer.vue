<script setup>
import { onMounted, provide, ref } from 'vue';

const props = defineProps({
    initialActiveTab: {
        type: String,
        default: '',
    }
});

const tabs = ref([]);
const activeTab = ref(props.initialActiveTab);

provide('tabs', tabs);
provide('activeTab', activeTab);

const selectTab = (tabName) => {
    activeTab.value = tabName;
}

provide('selectTab', selectTab);

onMounted(() => {
    if (!props.initialActiveTab && tabs.value.length > 0) {
        activeTab.value = tabs.value[0].name;
    }
});
</script>

<template>
    <div>
        <div class="flex border-b border-gray-200">
            <button
                v-for="(tab, index) in tabs"
                :key="index"
                :class="['py-2 px-4 focus:outline-none', activeTab === tab.name ? 'border-b-2 border-blue-500 text-blue-500 font-semibold' : 'text-gray-500 hover:text-gray-700']"
                @click="selectTab(tab.name)"
            >
                {{ tab.label }}
            </button>
        </div>
        <div class="p-4">
            <slot :name="activeTab"></slot>
        </div>
    </div>
</template>
