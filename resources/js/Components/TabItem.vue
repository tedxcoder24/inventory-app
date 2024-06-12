<script setup>
import { computed, inject, onMounted } from 'vue';

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
});

const tabs = inject('tabs');
const activeTab = inject('activeTab');
const selectTab = inject('selectTab');

const isActive = computed(() => activeTab.value === props.name);

onMounted(() => {
    tabs.value.push({
        name: props.name,
        label: props.label,
    });
});
</script>

<template>
    <div v-if="isActive">
        <slot></slot>
    </div>
</template>
