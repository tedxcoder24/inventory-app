<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AttributeTable from '@/Components/AttributeTable.vue';

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
});
</script>

<template>
    <Head title="Attributes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Attributes</h2>
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

                            <AttributeTable header="clarity" :attributes="clarities.data" />
                        </div>

                        <div>
                            <label>Appearance</label>

                            <AttributeTable header="appearance" :attributes="appearances.data" />
                        </div>

                        <div>
                            <label>Status</label>

                            <AttributeTable header="status" :attributes="statuses.data" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
