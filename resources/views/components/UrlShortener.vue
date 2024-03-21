<template>
    <div class="shadow-xl container mx-auto px-4 py-8 bg-gray-200 rounded-2xl shadow-md m-5">
        <h1 class="text-5xl font-semibold text-center">URL Shortener</h1>
        <form @submit.prevent="shortenUrl" class="m-6">
            <div class="flex flex-col mb-4">
                <label for="longUrl" class="text-sm font-normal text-gray-700 mb-1">Enter a long URL:</label>
                <input type="text" id="longUrl" v-model="longUrl" required
                    class="shadow-xl appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            </div>

            <div class="flex flex-col mb-4">
                <label for="folder" class="text-sm font-normal text-gray-700 mb-1">Enter a folder (Optional):</label>
                <input type="text" id="folder" v-model="folder"
                    class="shadow-xl appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            </div>

            <button type="submit"
                class="shadow-xl bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">Shorten</button>
        </form>

        <div v-if="message" class="alert shadow rounded-md p-3 mx-6 shadow-xl"
            :class="{ 'bg-green-400 text-white': !isMessageError, 'bg-red-500 text-white': isMessageError }"
            role="alert">
            <span v-if="isMessageError" class="font-bold">Error: </span>
            <span v-else class="font-bold">Shortened URL: </span>
            <span v-if="isMessageError">{{ message }}</span>
            <span v-else><a :href="message" target="_blank" class="text-blue-500 hover:underline">{{ message
                    }}</a></span>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            longUrl: '',
            folder: null,
            isMessageError: null,
            message: null,
        };
    },
    methods: {
        shortenUrl() {
            const data = { long_url: this.longUrl, folder: this.folder };
            const url = this.folder ? '/shorten-foldered-url' : '/shorten-url';
            axios.post(url, data)
                .then(response => {
                    this.message = response.data.url;
                    this.isMessageError = false;
                })
                .catch(error => {
                    this.message = error.response.data.message || 'An error occurred while shortening the URL.';
                    this.isMessageError = true;
                });
        },
    },
};
</script>

<style scoped>
</style>
