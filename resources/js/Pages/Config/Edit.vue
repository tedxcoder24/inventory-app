<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Select from '@/Components/Select.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    config_data: {
        type: Object,
        required: true
    },
});

const form = useForm({
    last_serial_number: props.config_data.data[0].last_serial_number,
    serial_port: props.config_data.data[0].serial_port,
    label_printer: props.config_data.data[0].label_printer,
    report_printer: props.config_data.data[0].report_printer,
    image_directory: props.config_data.data[0].image_directory,
    baud_rate: props.config_data.data[0].baud_rate,
    data_bits: props.config_data.data[0].data_bits,
    stop_bits: props.config_data.data[0].stop_bits,
    parity: props.config_data.data[0].parity,
});

const baudrates = [
    { value: 9600, text: 9600 },
    { value: 19200, text: 19200 },
    { value: 38400, text: 38400 },
    { value: 57600, text: 57600 },
    { value: 115200, text: 115200 },
];

const databits = [
    { value: 7, text: 7 },
    { value: 8, text: 8 },
];

const stopbits = [
    { value: 1, text: 1 },
    { value: 2, text: 2 },
];

const parity = [
    { value: 'none', text: 'none' },
    { value: 'even', text: 'even' },
    { value: 'odd', text: 'odd' },
];

const submit = () => {
    form.put(route('config.update', 1), {
        preserveScroll: true
    });
}

const cancel = () => {
    form.get(route('config.index'), {});
}
</script>

<template>
    <Head title="Edit Config" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Item</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-900 mb-6">Select attributes for config.</p>

                        <form class="flex flex-col gap-8" @submit.prevent="submit">
                            <div class="flex justify-around">
                                <div>
                                    <InputLabel for="serial_number" value="Last Serial Number" />

                                    <TextInput
                                        id="serial_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.last_serial_number"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="flex justify-around">
                                <div>
                                    <InputLabel for="serial_port" value="Serial Port" />

                                    <TextInput
                                        id="serial_port"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.serial_port"
                                        required
                                    />
                                </div>

                                <div>
                                    <InputLabel for="label_printer" value="Label Printer" />

                                    <TextInput
                                        id="label_printer"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.label_printer"
                                        required
                                    />
                                </div>

                                <div>
                                    <InputLabel for="report_printer" value="Report Printer" />

                                    <TextInput
                                        id="report_printer"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.report_printer"
                                        required
                                    />
                                </div>

                                <div>
                                    <InputLabel for="image_directory" value="Image Directory" />

                                    <TextInput
                                        id="image_directory"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.image_directory"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="flex justify-around">
                                <div>
                                    <InputLabel for="baudrate" value="Baud Rate" />
        
                                    <Select
                                        id="baudrate"
                                        :options="baudrates"
                                        v-model="form.baud_rate"
                                        class="mt-1 block w-full"
                                        autofocus
                                        required
                                    />
                                </div>

                                <div>
                                    <InputLabel for="databits" value="Data Bits" />
        
                                    <Select
                                        id="databits"
                                        :options="databits"
                                        v-model="form.data_bits"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                </div>

                                <div>
                                    <InputLabel for="stopbits" value="Stop Bits" />
        
                                    <Select
                                        id="stopbits"
                                        :options="stopbits"
                                        v-model="form.stop_bits"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                </div>

                                <div>
                                    <InputLabel for="parity" value="Parity" />
        
                                    <Select
                                        id="parity"
                                        :options="parity"
                                        v-model="form.parity"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <SecondaryButton @click="cancel"> Cancel </SecondaryButton>

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
