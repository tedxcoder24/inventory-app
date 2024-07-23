<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Select from '@/Components/Select.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    currentWeight: {
        type: Object,
        required: true,
    },
    currentStatus: {
        type: Object,
        requried: true,
    },
    weightUnit: {
        type: Object,
        required: true,
    },
    statuses: { type: Object },
    operators: { type: Object },
});

const showStatusModal = ref(false);
const showWeightModal = ref(false);

const updateItemStatusForm = useForm({
    id: props.item.id,
    operator_id: props.item.operator_id,
    status_id: props.currentStatus.status.id,
    note: '',
});

const updateItemWeightForm = useForm({
    id: props.item.id,
    operator_id: props.item.operator_id,
    gross_weight: props.currentWeight.gross_weight,
    note: '',
});

const updateItemsStatus = () => {
    updateItemStatusForm.post(route('item.change-status'), {
        onFinish: () => {
            showStatusModal.value = false;
        }
    });
}

const updateItemsWeight = () => {
    updateItemWeightForm.post(route('item.change-weight'), {
        onFinish: () => {
            showWeightModal.value = false;
        }
    });
}
</script>

<template>
    <Head title="Inventory" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="flex justify-between items-center text-xl font-semibold leading-tight text-gray-800">
                <div>
                    Item
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <div>
                    <button
                        href="#"
                        @click="showStatusModal = true"
                        class="px-4 py-2 mr-3 text-sm text-green-600 transition border border-green-300 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent hover:cursor-pointer"
                    >
                        Change Status
                    </button>
                    <button
                        href="#"
                        @click="showWeightModal = true"
                        class="px-4 py-2 mr-3 text-sm text-green-600 transition border border-green-300 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent hover:cursor-pointer"
                    >
                        Change Weight
                    </button>
                </div>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-900 mb-6"> Item Details - History of Item change </p>

                        <div class="flex flex-col gap-8">
                            <div class="w-full">
                                <div class="flex flex-col justify-between items-center gap-6">
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Serial Number: </label> 
                                            {{ item.serial_number }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Date and Time: </label>
                                            {{ item.date_time }} 
                                        </div>
                                    </div>
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Operator: </label>
                                            {{ item.operator.operator }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Item Type: </label>
                                            {{ item.item_type.item_type }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Status: </label>
                                            {{ currentStatus.status.status }}
                                        </div>
                                    </div>
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Batch ID: </label>
                                            {{ item.batch_id }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Metric ID: </label>
                                            {{ item.metrc_id }} 
                                        </div>
                                    </div>
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Strain: </label>
                                            {{ item.strain.strain }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Product: </label>
                                            {{ item.product.product }} 
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Tare Weight: </label>
                                            {{ item.tare_weight.toFixed(2) }} (g)
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Gross Weight: </label>
                                            {{ currentWeight.gross_weight }} ({{ weightUnit.abbreviation }})
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Current Net Weight: </label>
                                            {{ (currentWeight.gross_weight - (item.tare_weight / weightUnit.convert_to_grams)).toFixed(2) }} ({{ weightUnit.abbreviation }})
                                        </div>
                                    </div>
                                    <div class="flex justify-around gap-6">
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Color: </label>
                                            {{ item.color.color }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Clarity: </label>
                                            {{ item.clarity.clarity }} 
                                        </div>
                                        <div class="text-lg font-medium text-gray-900"> 
                                            <label class="text-base font-medium text-gray-500"> Appearance: </label>
                                            {{ item.appearance.appearance }} 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full">
                                <div class="flex justify-around gap-6">
                                    <table class="block overflow-y-auto whitespace-nowrap divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Operator
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Date Time
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Status
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Note
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="(status, id) in item.statuses" :key="id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status.operator.operator }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status.date_time }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status.status.status }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ status.note }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="block overflow-y-auto whitespace-nowrap divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Operator
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Date Time
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Gross Weight
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                                    Note
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="(weight, id) in item.weights" :key="id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ weight.operator.operator }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ weight.date_time }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ weight.gross_weight }} ({{ weightUnit.abbreviation }})
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center justify-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ weight.note }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                        <InputLabel for="gross_weight" :value="`Weight (${weightUnit.abbreviation})`" />

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
    </AuthenticatedLayout>
</template>
