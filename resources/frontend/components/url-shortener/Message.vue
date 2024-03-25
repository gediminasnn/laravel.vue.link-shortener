<template>
    <div class="text-xs">
        <span v-if="isError" class="text-red-500 font-bold">Error: </span>
        <span v-else class="text-green-500">Shortened URL: </span>
        <span v-if="isError" class="text-red-500">{{ message }}</span>
        <a v-else :href="message" target="_blank" class="text-blue-500 hover:underline">{{ message }}</a>
    </div>
</template>

<script>
export default {
    props: {
        message: String,
        isError: Boolean,
    },
};
</script>

<style scoped></style>
