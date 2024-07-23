<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import StrainSelectForm from './Partials/StrainSelectForm.vue';

const props = defineProps({
    item: { type: Object },
    currentStatus: { type: Object },
    currentWeight: { type: Object },
    statuses: { type: Object },
    operators: { type: Object },
    itemTypes: { type: Object },
    strains: { type: Object },
    products: { type: Object },
    colors: { type: Object },
    clarities: { type: Object },
    appearances: { type: Object },
});

const form = useForm({
    operator_id: props.item.operator_id,
    item_type_id: props.item.item_type_id,
    batch_id: props.item.batch_id,
    metrc_id: props.item.metrc_id,
    tare_weight: props.item.tare_weight,
    gross_weight: props.currentWeight.gross_weight,
    strain_id: props.item.strain_id,
    product_id: props.item.product_id,
    color_id: props.item.color_id,
    clarity_id: props.item.clarity_id,
    appearance_id: props.item.appearance_id,
    status_id: props.currentStatus.id,
});

const showModal = ref(false);

const submitEditedItem = () => {
    form.put(route('items.update', props.item.id), {});
}

const cancel = () => {
    form.get(route('items.index'));
}
</script>

<template>
    <Head title="Edit Item" />
    
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Item</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-900 mb-6">Select attributes for an item.</p>

                        <div class="flex flex-col gap-8">
                            <div class="flex gap-6">
                                <div class="w-1/2">
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

                                <div class="w-1/2">
                                    <InputLabel for="item_type" value="Item Type *" />
                                    
                                    <Select
                                        id="item_type"
                                        :options="itemTypes.data"
                                        v-model="form.item_type_id"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="flex gap-6">
                                <div>
                                    <InputLabel for="status" value="Status *" />
        
                                    <Select
                                        id="status"
                                        :options="statuses.data"
                                        v-model="form.status_id"
                                        class="mt-1 block w-full"
                                        required
                                        disabled
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
                                            class="mt-1 block w-full"
                                            v-model="form.tare_weight"
                                            disabled
                                            required
                                        />

                                        <!-- <PrimaryButton>Get tare weight</PrimaryButton> -->
                                    </div>
                                </div>
    
                                <div class="w-1/2">
                                    <InputLabel for="gross_weight" :value="`Gross Weight (${itemTypes.data[form.item_type_id - 1] ? itemTypes.data.filter(v => v.value === Number(form.item_type_id))[0].weight_unit.abbreviation : ''}) *`" />
        
                                    <div class="flex gap-4">
                                        <TextInput
                                            id="gross_weight"
                                            type="number"
                                            class="mt-1 block w-full"
                                            v-model="form.gross_weight"
                                            disabled
                                            required
                                        />

                                        <!-- <PrimaryButton>Get gross weight</PrimaryButton> -->
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

                                <PrimaryButton @click="showModal = true">
                                    Save
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmationModal :show="showModal" @close="showModal = false">
            <template #title> Confirm </template>

            <template #content>
                Are you sure you would like to save this item?
            </template>

            <template #footer>
                <SecondaryButton @click="showCreateConfirmModal = false"> Cancel </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="submitEditedItem"
                >
                    Save
                </PrimaryButton>
            </template>
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>
