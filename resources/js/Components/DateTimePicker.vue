<script setup>
import { ref, watch, onMounted } from 'vue';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import { format } from 'date-fns';

const props = defineProps({
    modelValue: {
        type: Date,
        required: false,
        default: () => new Date(),
    },
    config: {
        type: Object,
        default: () => ({
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
        }),
    },
});

const localValue = ref(props.modelValue);

watch(() => props.modelValue, (newVal) => {
    if (newVal !== localValue.value) {
        localValue.value = newVal;
    }
});

const emit = defineEmits(['update:modelValue']);
const updateValue = (selectedDates) => {
    const formattedDate = format(selectedDates[0], 'yyyy-MM-dd HH:mm:ss');
    emit('update:modelValue', formattedDate);
};

onMounted(() => {
    if (!props.modelValue) {
        const now = new Date();
        localValue.value = now;
        updateValue([now]);
    }
});
</script>

<template>
    <flat-pickr
        v-model="localValue"
        :config="config"
        @on-change="updateValue"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    />
</template>
