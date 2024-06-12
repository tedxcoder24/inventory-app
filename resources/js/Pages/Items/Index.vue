<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';

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

const showModal = ref(false);
const selectedItems = ref([]);
const updateItemStatusForm = useForm({
    ids: [],
    date_time: new Date(),
    operator_id: 0,
    status_id: 0,
    note: '',
});

const isAllSelected = computed({
    get() {
        return selectedItems.value.length === props.items.data.length;
    },
    set(value) {
        if (value) {
            selectedItems.value = props.items.data.map(item => item.id);
        } else {
            selectedItems.value = [];
        }
    },
});

const isIndeterminate = computed(() => {
  return (
    selectedItems.value.length > 0 &&
    selectedItems.value.length < props.items.data.length
  );
});

const toggleSelectAll = (value) => {
  isAllSelected.value = value;
};

const updateSelectedItems = (value) => {
  selectedItems.value = value;
};

const deleteItem = (item) => {
    if (!confirm("Are you sure want to delete item?")) return;

    router.delete(route('items.destroy', item.id));
}

const batchEdit = () => {
    showModal.value = true;
}

const updateItems = () => {
    updateItemStatusForm.ids = selectedItems.value;
    updateItemStatusForm.post(route("item.batch-change-status"), {
        onFinish: () => {
            showModal.value = false;
        }
    });
}
</script>

<template>
    <Head title="Inventory" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="flex justify-between text-xl font-semibold leading-tight text-gray-800">
                <p>
                    Items
                    <i class="fa-solid fa-user-gear"></i>
                </p>
                <Link 
                    :href="route('items.create')"
                    class="px-4 py-2 mr-3 text-sm text-green-600 transition border border-green-300 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent hover:cursor-pointer"
                >
                    Add Item
                </Link>
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex flex-col gap-8">
                        <div class="flex justify-end">
                            <PrimaryButton class="hover:cursor-pointer" :disabled="selectedItems.length === 0" :class="{ 'hover:cursor-not-allowed': selectedItems.length === 0 }" @click="batchEdit">Batch Edit</PrimaryButton>
                        </div>
                        <div class="flex justify-center">
                            <table class="block overflow-y-auto whitespace-nowrap divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            <label class="flex items-center justify-center">
                                                <Checkbox 
                                                    name="check-all" 
                                                    :checked="isAllSelected"
                                                    :indeterminate="isIndeterminate"
                                                    @update:checked="toggleSelectAll"
                                                />
                                            </label>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Operator
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Date Time
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Serial Number
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Type
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Batch ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Metric ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Tare Weight
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Gross Weight
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Weight Unit
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Strain
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Product
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Color
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Clarity
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            Appearance
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="item in items.data" :key="item.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <label class="flex items-center justify-center">
                                                <Checkbox 
                                                    name="check-all" 
                                                    :checked="selectedItems"
                                                    :value="item.id"
                                                    @update:checked="updateSelectedItems"
                                                />
                                            </label>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.operator }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.date_time }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.serial_number }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.item_type }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.batch_id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.metrc_id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.tare_weight }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.gross_weight }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.weight_unit }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.strain }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.product }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                    {{ item.color }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.clarity }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ item.appearance }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <Link
                                                    :href="`/items/${item.id}`"
                                                    class="float-left px-4 py-2 text-green-400 duration-100 rounded hover:text-green-600"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye w-6 h-6" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                                    </svg>
                                                </Link>
                                                <Link
                                                    :href="`/items/${item.id}/edit`"
                                                    class="float-left px-4 py-2 text-green-400 duration-100 rounded hover:text-green-600"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="w-6 h-6"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </Link>
                                                <a
                                                    href="#"
                                                    @click="deleteItem(item)"
                                                    class="float-left px-4 py-2 ml-2 text-red-400 duration-100 rounded hover:text-red-600"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="w-6 h-6"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        There are {{ items.data.length }} items.
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <DialogModal :show="showModal" @close="showModal = false">
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
                <SecondaryButton @click="showModal = false"> Close </SecondaryButton>
                
                <PrimaryButton 
                    class="ml-4" 
                    :class="{ 'opacity-25': updateItemStatusForm.processing }"
                    :disabled="updateItemStatusForm.processing"
                    @click="updateItems"
                > 
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>
    </AuthenticatedLayout>
</template>
