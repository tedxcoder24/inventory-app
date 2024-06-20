<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AttributeTable from '@/Components/AttributeTable.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Select from '@/Components/Select.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

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
        required: true
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
    statuses: {
        type: Object,
        required: true,
    },
    attr_types: {
        type: Array,
        required: true,
    },
});

const showModal = ref(false);

const form = useForm({
    value: '',
    type: '',
});

const createAttribute = () => {
    form.post(route('attributes.store'), {
        onFinish: () => {
            showModal.value = false;
        }
    });
}
</script>

<template>
    <Head title="Attributes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Attributes</h2>
                </div>
                <div>
                    <a
                        href="#"
                        @click="showModal = true"
                        class="px-4 py-2 mr-3 text-sm text-green-600 transition border border-green-300 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent hover:cursor-pointer"
                    >
                        Add attribute
                    </a>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex flex-wrap justify-around items-start gap-16">
                        <div>
                            <label>Operators</label>

                            <AttributeTable header="Operator" :attributes="operators.data" />
                        </div>

                        <div>
                            <label>Item Types</label>

                            <AttributeTable header="Item Type" :attributes="itemTypes.data" />
                        </div>

                        <div>
                            <label>Strain</label>

                            <AttributeTable header="Strain" :attributes="strains.data" />
                        </div>

                        <div>
                            <label>Products</label>

                            <AttributeTable header="Product" :attributes="products.data" />
                        </div>

                        <div>
                            <label>Colors</label>

                            <AttributeTable header="Color" :attributes="colors.data" />
                        </div>

                        <div>
                            <label>Clarity</label>

                            <AttributeTable header="Clarity" :attributes="clarities.data" />
                        </div>

                        <div>
                            <label>Appearance</label>

                            <AttributeTable header="Appearance" :attributes="appearances.data" />
                        </div>

                        <div>
                            <label>Status</label>

                            <AttributeTable header="Status" :attributes="statuses.data" />
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <DialogModal :show="showModal" @close="showModal = false">
            <template #title> Attribute Create </template>

            <template #content>
                <div class="flex flex-col gap-8">
                    <div> Create an attribute </div>

                    <div>
                        <InputLabel for="type" value="Type" />

                        <Select
                            id="type"
                            :options="attr_types"
                            v-model="form.type"
                            class="mt-1 block w-full"
                            autofocus
                            required
                        />
                    </div>

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
                <SecondaryButton @click="showModal = false"> Close </SecondaryButton>
                
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
    </AuthenticatedLayout>
</template>
