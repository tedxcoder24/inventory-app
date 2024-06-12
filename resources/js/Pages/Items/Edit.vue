<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';

const props = defineProps({
    item: {
        type: Object,
    },
    statuses: {
        type: Object,
    },
    operators: {
        type: Object,
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

const form = useForm({
    operator_id: props.item.operator_id,
    date_time: props.item.date_time,
    item_type_id: props.item.item_type_id,
    batch_id: props.item.batch_id,
    metrc_id: props.item.metrc_id,
    tare_weight: props.item.tare_weight,
    gross_weight: props.item.gross_weight,
    weight_unit_id: props.item.weight_unit_id,
    strain_id: props.item.strain_id,
    product_id: props.item.product_id,
    color_id: props.item.color_id,
    clarity_id: props.item.clarity_id,
    appearance_id: props.item.appearance_id,
});

const submit = () => {
    if (!confirm('Are you sure update this data?')) return;

    form.put(route("items.update", props.item.id), {});
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
        
        {{ statuses }}
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

                                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Save
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
