<template>
    <button type="submit"
        class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-3.5 px-4 rounded focus:ring-blue-700">{{ name }}</button>
</template>

<script lang="ts">
export default {
    props: {
        name: String,
    },
};
</script>

<style scoped></style>
