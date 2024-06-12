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

defineProps({
    operators: {
        type: Object,
        required: true,
    },
    itemTypes: {
        type: Object,
        required: true,
    },
    weightUnits: {
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
    operator_id: 0,
    date_time: new Date(),
    item_type_id: 0,
    batch_id: '',
    metrc_id: '',
    tare_weight: 0,
    gross_weight: 0,
    weight_unit_id: 0,
    strain_id: 0,
    product_id: 0,
    color_id: 0,
    clarity_id: 0,
    appearance_id: 0,
});

const confirmItemCreate = () => {
    showCreateConfirmModal.value = true;
}

const submit = () => {
    if (!confirm("Are you sure want to save item?")) return;

    form.post(route('items.store'), {
        onFinish: () => form.reset('operator'),
    });
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

                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="operator" value="Operator" />

                                <Select
                                    id="operator"
                                    :options="operators.data"
                                    v-model="form.operator_id"
                                    class="mt-1 block w-full"
                                    autofocus
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="date_time" value="Date and Time" />

                                <DateTimePicker
                                    id="date_time"
                                    class="mt-1 block"
                                    required
                                    v-model="form.date_time"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="item_type" value="Item Type" />
                                
                                <Select
                                    id="item_type"
                                    :options="itemTypes.data"
                                    v-model="form.item_type_id"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="batch_id" value="Batch ID" />
    
                                <TextInput
                                    id="batch_id"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.batch_id"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="metrc_id" value="Metric ID" />
    
                                <TextInput
                                    id="metrc_id"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.metrc_id"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="tare_weight" value="Tare Weight" />
    
                                <TextInput
                                    id="tare_weight"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.tare_weight"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="gross_weight" value="Gross Weight" />
    
                                <TextInput
                                    id="gross_weight"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.gross_weight"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="weight_unit" value="Weight Unit" />
    
                                <Select
                                    id="weight_unit"
                                    :options="weightUnits.data"
                                    v-model="form.weight_unit_id"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>
                         
                            <div class="mt-4">
                                <InputLabel for="strain" value="Strain" />
    
                                <Select
                                    id="strain"
                                    :options="strains.data"
                                    v-model="form.strain_id"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="product" value="Product" />
    
                                <Select
                                    id="product"
                                    :options="products.data"
                                    v-model="form.product_id"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="color" value="Color" />
    
                                <Select
                                    id="color"
                                    :options="colors.data"
                                    v-model="form.color_id"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>
                            
                            <div class="mt-4">
                                <InputLabel for="clarity" value="Clarity" />

                                <Select
                                    id="clarity"
                                    :options="clarities.data"
                                    v-model="form.clarity_id"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="appearance" value="Appearance" />
    
                                <Select
                                    id="appearance"
                                    :options="appearances.data"
                                    v-model="form.appearance_id"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <SecondaryButton @click="cancel">Cancel</SecondaryButton>

                                <PrimaryButton class="ms-4" @click="confirmItemCreate">
                                    Save
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmationModal :show="showCreateConfirmModal" @close="showCreateConfirmModal = false">
            <template #title> Create Item </template>

            <template #content>
                Are you sure you would like to create this item?
            </template>

            <template #footer>
                <SecondaryButton @click="showCreateConfirmModal = false"> Cancel </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="submit"
                >
                    Create
                </PrimaryButton>
            </template>
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>
