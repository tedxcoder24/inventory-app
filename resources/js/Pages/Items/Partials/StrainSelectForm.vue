<script setup>
import { ref, watch } from 'vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    options: {
      type: Array,
      required: true,
    },
    modelValue: {
      type: [String, Number],
      required: true,
    },
});

const emit = defineEmits(['update:modelValue', 'addStrain']);

const select = ref(null);
const addingStrain = ref(false);
const newStrain = ref('');

const handleSelectChange = (event) => {
    if (event.target.value === 'add-new') {
        addingStrain.value = true;
        select.value.value = '';
    } else {
        emit('update:modelValue', event.target.value);
    }
}

watch(newStrain, (value) => {
    if (value) {
        emit('addStrain', value);
        addingStrain.value = false;
        newStrain.value = '';
    }
});

const form = useForm({
    value: '',
    type: '2',
});

const createAttribute = () => {
    form.post(route('attributes.store'), {
        onFinish: () => {
            addingStrain.value = false;
        }
    });
}
</script>

<template>
    <select
        :value="modelValue"
        @change="handleSelectChange"
        ref="select"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
    >
        <option
            v-for="option in options" 
            :key="option.value" 
            :value="option.value"
            class="text-base"
        >
            {{ option.text }}
        </option>
        <option value="add-new" class="text-base font-bold">Add New Strain</option>
    </select>

    <DialogModal :show="addingStrain" @close="addingStrain = false">
        <template #title> Add a new Strain </template>

        <template #content>
            <div class="flex flex-col gap-8">
                <div class="mt-4">
                    <InputLabel for="value" value="Value" />

                    <TextInput
                        id="value"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.value"
                    />
                </div>
            </div>
        </template>

        <template #footer>
            <SecondaryButton @click="addingStrain = false"> Close </SecondaryButton>
            
            <PrimaryButton
                class="ml-4" 
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="createAttribute"
            > 
                Create
            </PrimaryButton>
        </template>
    </DialogModal>
</template>
