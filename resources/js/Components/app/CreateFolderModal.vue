<script setup>
//IMPORTS
import { useForm } from "@inertiajs/vue3";
import Modal from "../Modal.vue";
import SecondaryButton from '../SecondaryButton.vue'
import PrimaryButton from '../PrimaryButton.vue'
import InputLabel from '../InputLabel.vue'
import TextInput from '../TextInput.vue'
import InputError from '../InputError.vue'
import { nextTick, ref } from "vue";

//USES
const form = useForm({
    name: "",
});

//REFS
const folderNameInput = ref(null);

//PROPS AND EMITS
const { modelValue } = defineProps({
    modelValue: Boolean,
});

const emit = defineEmits(["update:modelValue"]);

//METHODS
const createFolder = () => {
    form.post(route("folder.create"), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            form.reset();
            //show success notification
        },
        onError: () => {
            folderNameInput.value.focus();
        }
    })
}

const onShow = () => {
    nextTick(() => folderNameInput.value.focus());
}
const closeModal = () => {
    emit("update:modelValue", false);
    form.clearErrors();
    form.reset();
}
</script>

<template>
    <Modal :show="modelValue" @show="onShow" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">Create New Folder</h2>
            <div class="mt-6">
                <InputLabel for="folderName" value="Folder Name" class="sr-only"/>
                <TextInput
                    ref="folderNameInput"
                    type="text"
                    id="folderName"
                    v-model="form.name"
                    class="mt-1 block w-full"
                    :class="
                        form.errors.name
                            ? 'border-red-500 focus: border-red-500 focus: ring-red-500'
                            : ''
                    "
                    placeholder="Folder Name"
                    @keyup.enter="createFolder"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                <PrimaryButton @click="createFolder" class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> Create </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
