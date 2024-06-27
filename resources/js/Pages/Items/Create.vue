<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import StrainSelectForm from './Partials/StrainSelectForm.vue';

defineProps({
    operators: {
        type: Object,
        required: true,
    },
    itemTypes: {
        type: Object,
        required: true,
    },
    strains: {
        type: Object,
        required: true,
    },
    products: {
        type: Object,
        required: true,
    },
    colors: {
        type: Object,
        required: true,
    },
    clarities: {
        type: Object,
        required: true,
    },
    appearances: {
        type: Object,
        required: true,
    },
});

const showCreateConfirmModal = ref(false);

const form = useForm({
    operator_id: "1",
    date_time: new Date(),
    item_type_id: "1",
    batch_id: "",
    metrc_id: "",
    tare_weight: 0,
    gross_weight: 0,
    strain_id: "1",
    product_id: "1",
    color_id: "",
    clarity_id: "",
    appearance_id: "",
});

const createItem = () => {
    form.post(route('items.store'), {
        onFinish: () => {
            showCreateConfirmModal.value = false;

            form.date_time = new Date();
            form.batch_id = '';
            form.metrc_id = '';
            form.gross_weight = 0;
            form.color_id = '';
            form.clarity_id = '';
            form.appearance_id = '';
        },
    });
}

const submit = () => {
    showCreateConfirmModal.value = true;
}

const cancel = () => {
    form.get(route('items.index'));
}
</script>

<template>
    <Head title="Add Item" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Item</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-900 mb-6">Select attributes for an item.</p>

                        <form class="flex flex-col gap-8" @submit.prevent="submit">
                            <div class="flex gap-6">
                                <div class="w-1/3">
                                    <InputLabel for="operator" value="Operator *" />

                                    <Select
                                        id="operator"
                                        :options="operators.data"
                                        v-model="form.operator_id"
                                        class="mt-1 block w-full"
                                        autofocus
                                        required
                                    />
                                </div>

                                <div class="w-1/3">
                                    <InputLabel for="item_type" value="Item Type *" />
                                    
                                    <Select
                                        id="item_type"
                                        :options="itemTypes.data"
                                        v-model="form.item_type_id"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                </div>

                                <div class="w-1/3">
                                    <InputLabel for="date_time" value="Date and Time" />

                                    <DateTimePicker
                                        id="date_time"
                                        class="mt-1 block"
                                        required
                                        v-model="form.date_time"
                                    />
                                </div>
                            </div>

                            <div class="flex gap-6">
                                <div class="w-1/2">
                                    <InputLabel for="batch_id" value="Batch ID (optional)" />
        
                                    <TextInput
                                        id="batch_id"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.batch_id"
                                    />
                                </div>
    
                                <div class="w-1/2">
                                    <InputLabel for="metrc_id" value="Metric ID (optional)" />
        
                                    <TextInput
                                        id="metrc_id"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.metrc_id"
                                    />
                                </div>
                            </div>

                            <div class="flex gap-6">
                                <div class="w-1/2">
                                    <InputLabel for="strain" value="Strain *" />
        
                                    <StrainSelectForm 
                                        id="strain"
                                        :options="strains.data"
                                        v-model="form.strain_id"
                                        @addStrain=""
                                        class="mt-1 block w-full"
                                        required
                                    />
                                </div>
    
                                <div class="w-1/2">
                                    <InputLabel for="product" value="Product *" />
        
                                    <Select
                                        id="product"
                                        :options="products.data"
                                        v-model="form.product_id"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="flex gap-6">
                                <div class="w-1/2">
                                    <InputLabel for="tare_weight" value="Tare Weight (g)" />
        
                                    <div class="flex gap-4">
                                        <TextInput
                                            id="tare_weight"
                                            type="number"
                                            step="0.01"
                                            class="mt-1 block w-full"
                                            v-model="form.tare_weight"
                                            required
                                        />

                                        <PrimaryButton>Get tare weight</PrimaryButton>
                                    </div>
                                </div>
    
                                <div class="w-1/2">
                                    <InputLabel for="gross_weight" :value="`Gross Weight (${itemTypes.data[form.item_type_id - 1] ? itemTypes.data[form.item_type_id - 1]?.weight_unit.abbreviation : ''}) *`" />
        
                                    <div class="flex gap-4">
                                        <TextInput
                                            id="gross_weight"
                                            type="number"
                                            step="0.01"
                                            class="mt-1 block w-full"
                                            v-model="form.gross_weight"
                                            required
                                        />

                                        <PrimaryButton>Get gross weight</PrimaryButton>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-6">
                                <div class="w-1/3">
                                    <InputLabel for="color" value="Color (optional)" />
        
                                    <Select
                                        id="color"
                                        :options="colors.data"
                                        v-model="form.color_id"
                                        class="mt-1 block w-full"
                                    />
                                </div>
                                
                                <div class="w-1/3">
                                    <InputLabel for="clarity" value="Clarity (optional)" />
    
                                    <Select
                                        id="clarity"
                                        :options="clarities.data"
                                        v-model="form.clarity_id"
                                        class="mt-1 block w-full"
                                    />
                                </div>
    
                                <div class="w-1/3">
                                    <InputLabel for="appearance" value="Appearance (optional)" />
        
                                    <Select
                                        id="appearance"
                                        :options="appearances.data"
                                        v-model="form.appearance_id"
                                        class="mt-1 block w-full"
                                    />
                                </div>
                            </div>

                            <div class="flex justify-end gap-6">
                                <SecondaryButton @click="cancel">Cancel</SecondaryButton>

                                <PrimaryButton>
                                    Save
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmationModal :show="showCreateConfirmModal" @close="showCreateConfirmModal = false">
            <template #title> Confirm </template>

            <template #content>
                Are you sure you would like to create this item?
            </template>

            <template #footer>
                <SecondaryButton @click="showCreateConfirmModal = false"> Cancel </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="createItem"
                >
                    Create
                </PrimaryButton>
            </template>
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>
