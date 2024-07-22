<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';
import SearchInput from '@/Components/SearchInput.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';

import { VDataTable, VTextField, VBtn, VIcon } from 'vuetify/lib/components/index.mjs';

const props = defineProps({
    items: {
        type: Object,
    },
    statuses: {
        type: Object,
    },
    operators: {
        type: Object,
    }
});

const page = usePage();
const role = computed(() => page.props.auth.user.role);

const showStatusModal = ref(false);
const showWeightModal = ref(false);
const updateItemStatusForm = useForm({
    ids: [],
    date_time: new Date(),
    operator_id: 0,
    status_id: 0,
    note: '',
});

const updateItemWeightForm = useForm({
    ids: [],
    date_time: new Date(),
    operator_id: 0,
    gross_weight: 0,
    note: '',
});

const showDeleteConfirmModal = ref(false);
const itemToDelete = ref();

const deleteItem = () => {
    router.delete(route('items.destroy', itemToDelete.value.id), {
        onFinish: () => {
            showDeleteConfirmModal.value = false;
        }
    });
}

const batchStatusEdit = () => {
    showStatusModal.value = true;
}

const batchWeightEdit = () => {
    showWeightModal.value = true;
}

const updateItemsStatus = () => {
    updateItemStatusForm.ids = searchedItems.value.map(item => item.id);
    updateItemStatusForm.post(route("item.batch-change-status"), {
        onFinish: () => {
            showStatusModal.value = false;
        }
    });
}

const updateItemsWeight = () => {
    updateItemWeightForm.ids = searchedItems.value.map(item => item.id);
    updateItemWeightForm.post(route("item.batch-change-weight"), {
        onFinish: () => {
            showWeightModal.value = false;
        }
    });
}

const searchedItems = ref([]);
const handleSearchedItems = (items) => {
    searchedItems.value = items;
}

const showItem = (item) => {
    router.visit(route('items.show', item.id));
}

const editItem = (item) => {
    router.visit(route('items.edit', item.id));
}

const openDeleteItemModal = (item) => {
    showDeleteConfirmModal.value = true;
    itemToDelete.value = item;
}

const headers = ref([
    { title: 'Serial Number', key: 'serial_number' },
    { title: 'Strain', key: 'strain' },
    { title: 'Type', key: 'item_type' },
    { title: 'Product', key: 'product' },
    { title: 'Status', key: 'status' },
    { title: 'Net Weight', key: 'net_weight' },
    { title: 'Color', key: 'color' },
    { title: 'Clarity', key: 'clarity' },
    { title: 'Appearance', key: 'appearance' },
    { title: 'Batch ID', key: 'batch_id' },
    { title: 'Metric ID', key: 'metrc_id' },
    { title: 'Tare Weight', key: 'tare_weight' },
    { title: 'Gross Weight', key: 'gross_weight' },
    { title: 'Date Time', key: 'date_time' },
    { title: 'Operator', key: 'operator' },
    { title: 'Actions', key: 'actions', sortable: false },
]);
</script>

<template>
    <Head title="Inventory" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Items
                    </h2>
                </div>
                <div>
                    <Link 
                        :href="route('items.create')"
                        class="px-4 py-2 mr-3 text-sm text-green-600 transition border border-green-300 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent hover:cursor-pointer"
                    >
                        Add Item
                    </Link>
                    <button class="px-4 py-2 mr-3 text-sm text-green-600 transition border border-green-300 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent hover:cursor-pointer" :disabled="searchedItems.length === 0" :class="{ 'hover:cursor-not-allowed': searchedItems.length === 0 }" @click="batchStatusEdit">Batch Status Change</button>
                    <button class="px-4 py-2 mr-3 text-sm text-green-600 transition border border-green-300 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent hover:cursor-pointer" :disabled="searchedItems.length === 0 || searchedItems.length > 1" :class="{ 'hover:cursor-not-allowed': searchedItems.length === 0 }" @click="batchWeightEdit"> Change Weight </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex flex-col gap-8">
                        <div class="flex justify-end">
                            <SearchInput :items="items.data" @update:selected-items="handleSearchedItems" />
                        </div>

                        <div class="flex justify-center">
                            <v-data-table
                                :headers="headers"
                                :items="items.data"
                                :items-per-page="10"
                                item-value="id"
                            >
                                <template v-slot:[`item.actions`]="{ item }">
                                    <v-btn icon @click="showItem(item)"><v-icon size="small">mdi-eye</v-icon></v-btn>
                                    <v-btn icon @click="editItem(item)"><v-icon size="small">mdi-pencil</v-icon></v-btn>
                                    <v-btn icon @click="openDeleteItemModal(item)"><v-icon size="small">mdi-delete</v-icon></v-btn>
                                </template>
                            </v-data-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <DialogModal :show="showStatusModal" @close="showStatusModal = false">
            <template #title> Status Update </template>

            <template #content>
                <div class="flex flex-col gap-8">
                    <div> Update the status of the items. </div>

                    <div>
                        <InputLabel for="operator" value="Operator" />

                        <Select
                            id="operator"
                            :options="operators.data"
                            v-model="updateItemStatusForm.operator_id"
                            class="mt-1 block w-full"
                            autofocus
                            required
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="status" value="Status" />

                        <Select
                            id="status"
                            :options="statuses.data"
                            v-model="updateItemStatusForm.status_id"
                            class="mt-1 block w-full"
                            required
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="date_time" value="Date and Time" />

                        <DateTimePicker
                            id="date_time"
                            class="mt-1 block"
                            required
                            v-model="updateItemStatusForm.date_time"
                        />
                    </div>

                    <div>
                        <InputLabel for="note" value="Note (optional)" />

                        <TextInput 
                            id="note"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="updateItemStatusForm.note"
                        />
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="showStatusModal = false"> Close </SecondaryButton>
                
                <PrimaryButton 
                    class="ml-4" 
                    :class="{ 'opacity-25': updateItemStatusForm.processing }"
                    :disabled="updateItemStatusForm.processing"
                    @click="updateItemsStatus"
                > 
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>

        <DialogModal :show="showWeightModal" @close="showWeightModal = false">
            <template #title> Weight Update </template>

            <template #content>
                <div class="flex flex-col gap-8">
                    <div> Update the weight of the items. </div>

                    <div>
                        <InputLabel for="operator" value="Operator" />

                        <Select
                            id="operator"
                            :options="operators.data"
                            v-model="updateItemWeightForm.operator_id"
                            class="mt-1 block w-full"
                            autofocus
                            required
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="gross_weight" value="Weight" />

                        <div class="flex gap-4">
                            <TextInput
                                id="gross_weight"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="updateItemWeightForm.gross_weight"
                                required
                            />

                            <PrimaryButton>Get gross weight</PrimaryButton>
                        </div>
                    </div>

                    <div class="mt-4">
                        <InputLabel for="date_time" value="Date and Time" />

                        <DateTimePicker
                            id="date_time"
                            class="mt-1 block"
                            required
                            v-model="updateItemWeightForm.date_time"
                        />
                    </div>

                    <div>
                        <InputLabel for="note" value="Note (optional)" />

                        <TextInput 
                            id="note"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="updateItemWeightForm.note"
                        />
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="showWeightModal = false"> Close </SecondaryButton>
                
                <PrimaryButton 
                    class="ml-4" 
                    :class="{ 'opacity-25': updateItemWeightForm.processing }"
                    :disabled="updateItemWeightForm.processing"
                    @click="updateItemsWeight"
                > 
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>

        <ConfirmationModal :show="showDeleteConfirmModal" @close="showDeleteConfirmModal = false">
            <template #title> Confirm </template>

            <template #content>
                Are you sure you would like to delete this item?
            </template>

            <template #footer>
                <SecondaryButton @click="showDeleteConfirmModal = false"> Cancel </SecondaryButton>

                <DangerButton class="ml-3" @click="deleteItem"> Delete </DangerButton>
            </template>
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>
