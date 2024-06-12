<script setup>
import { ref } from 'vue';

defineProps({
    options: {
        type: Array,
        required: true
    },
    modelValue: {
        type: [String, Number],
        required: true
    }
});

const emit = defineEmits(["update:modelValue"]);

const select = ref(null);
</script>

<template>
    <select
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="select"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >
        <option
            v-for="option in options" 
            :key="option.value" 
            :value="option.value"
            class="text-base"
        >
            {{ option.text }}
        </option>
    </select>
</template>