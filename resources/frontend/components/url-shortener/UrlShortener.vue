<template>
    <div class="mx-auto bg-white rounded-3xl m-5 max-w-4xl flex items-center flex-col md:flex-row truncate">
        <div class="flex-1 mt-7 md:mt-0">
            <h1 class="text-5xl font-extralight text-center">URL Shortener</h1>
        </div>
        <div class="flex-1">
            <ShortenerForm />
        </div>
    </div>
</template>

<script lang="ts">
import ShortenerForm from './ShortenerForm.vue';

export default {
    components: {
        ShortenerForm,
    },
};
</script>

<style scoped></style>
