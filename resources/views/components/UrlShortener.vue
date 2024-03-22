<template>
    <div class="container mx-auto px-4 py-px bg-white rounded-3xl m-5">
        <h1 class="text-5xl font-extralight text-center my-4">URL Shortener</h1>
        <div v-if="message" class="alert rounded-md px-3 py-2 mx-6"
            :class="{ 'bg-green-400 text-white': !isMessageError, 'bg-red-500 text-white': isMessageError }"
            role="alert">
            <span v-if="isMessageError" class="font-bold">Error: </span>
            <span v-else class="drop-shadow-md">Shortened URL: </span>
            <span v-if="isMessageError">{{ message }}</span>
            <span v-else><a :href="message" target="_blank" class="text-blue-500 hover:underline">{{ message
                    }}</a></span>
        </div>
        <form @submit.prevent="shortenUrl" class="mx-6 mb-6 mt-3">
            <div class="flex flex-col mb-4">
                <input type="text" id="longUrl" v-model="longUrl" required placeholder="Enter an URL"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 placeholder:text-gray-600 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-white border-gray-600 h-14"
                    :class="{ 'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-white': !isMessageError, 'focus:outline-none focus:ring-2 focus:ring-red-500 border-red-500 border-white text-red-500 ': isMessageError }">
                </input>
            </div>

            <div class="flex flex-col mb-3">
                <input type="text" id="folder" v-model="folder" placeholder="Enter a folder (Optional)"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 placeholder:text-gray-600 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-white border-gray-600 h-14"
                    :class="{ 'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-white': !isMessageError, 'focus:outline-none focus:ring-2 focus:ring-red-500 border-red-500 border-white text-red-500 ': isMessageError }">
                </div>

            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-full focus:ring-blue-700">Shorten</button>
        </form>
    </div>
</template>

<script lang="ts">
import axios from 'axios';

export default {
    data() {
        return {
            longUrl: '',
            folder: null,
            isMessageError: null,
            message: null,
        } as { longUrl: string; folder: string | null; isMessageError: boolean | null; message: string | null };;
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

<style scoped></style>
