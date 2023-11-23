<script setup>
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue';


</script>

<template>
    <AuthenticatedLayout>
        {{ $page.url }}
    </AuthenticatedLayout>
</template>
