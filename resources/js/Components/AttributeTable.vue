<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import ConfirmationModal from './ConfirmationModal.vue';
import DangerButton from './DangerButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import DialogModal from './DialogModal.vue';
import InputLabel from './InputLabel.vue';
import TextInput from './TextInput.vue';
import Select from './Select.vue';

const props = defineProps({
    header: {
        type: String,
        required: true,
    },
    attributes: {
        type: Array,
        required: true,
    },
    weightUnits: {
        type: Array,
        required: false
    }
});

const attrToDelete = ref(null);
const attrToEdit = ref(null);
const newAttribute = ref('');
const weightUnit = ref();
const showModal = ref(false);
const showEditModal = ref(false);

const confirmAttributeDeletion = (attr) => {
    showModal.value = true;
    attrToDelete.value = attr
}

const updateAttribute = (attr) => {
    router.put(route('attributes.update', attr.value), {
        enabled: attr.enabled ? false : true,
        text: attr.text,
        attribute: props.header,
    });
}

const deleteAttr = () => {
    router.post(route('attributes.delete'), {
        attribute: attrToDelete.value,
        table: props.header,
    }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (showModal.value = false)
    });
}

const openEditModal = (attr) => {
    showEditModal.value = true;
    attrToEdit.value = attr;
    newAttribute.value = attr.text;
    weightUnit.value = attr.weight_unit.id
}

const editAttr = () => {
    const payload = {
        enabled: attrToEdit.value.enabled ? true : false,
        text: newAttribute.value,
        attribute: props.header
    }

    if (props.header === 'Item Type') {
        payload.weight_unit = weightUnit.value;
    }
    router.put(route('attributes.update', attrToEdit.value.value), payload, {
        onSuccess: () => (showEditModal.value = false)
    });
}
</script>

<template>
    <table class="block overflow-y-auto whitespace-nowrap divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                    No
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                    {{ header }}
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                    Enabled
                </th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Action</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(attr, id) in attributes" :key="id">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ id + 1 }}
                            </div>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ attr.text }}
                            </div>
                        </div>
                    </div>
                </td>

                <td v-if="attr.weight_unit" class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ attr.weight_unit.abbreviation }}
                            </div>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <a
                            href="#"
                            @click="updateAttribute(attr)"
                            :class="['px-4 py-2 text-sm hover:cursor-pointer transition border rounded-full hover:border-transparent', attr.enabled ? 'text-green-600 border-green-300 hover:bg-green-600 hover:text-white' : 'text-red-600 border-red-300 hover:bg-red-600 hover:text-white']"
                        >
                            {{ attr.enabled ? 'Yes' : 'No' }}
                        </a>
                    </div>
                </td>

                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <a
                            href="#"
                            @click="openEditModal(attr)"
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
                        </a>
                        <a
                            href="#"
                            @click="confirmAttributeDeletion(attr)"
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
        </tbody>
    </table>

    <ConfirmationModal :show="showModal" @close="showModal = false">
        <template #title> Delete attribute </template>

        <template #content>
            Are you sure you would like to delete this attribute?
        </template>

        <template #footer>
            <SecondaryButton @click="showModal = false"> Cancel </SecondaryButton>

            <DangerButton
                class="ml-3"
                @click="deleteAttr(attr)"
            >
                Delete
            </DangerButton>
        </template>
    </ConfirmationModal>

    <DialogModal :show="showEditModal" @close="showEditModal = false">
        <template #title> Edit attribute </template>

        <template #content>
            <div>
                <InputLabel for="edit_attribute" value="Attribute" />

                <TextInput 
                    id="edit_attribute"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="newAttribute"
                    required
                />
            </div>

            <div v-if="header === 'Item Type'">
                <InputLabel for="weightUnit" value="Weight Unit" />

                <Select 
                    id="weightUnit"
                    :options="weightUnits.data"
                    v-model="weightUnit"
                    class="mt-1 block w-full"
                />
            </div>
        </template>

        <template #footer>
            <SecondaryButton @click="showEditModal = false"> Cancel </SecondaryButton>

            <DangerButton
                class="ml-3"
                @click="editAttr(attr)"
            >
                Edit
            </DangerButton>
        </template>
    </DialogModal>
</template>
