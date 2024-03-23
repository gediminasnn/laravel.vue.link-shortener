<template>
    <div class="mx-auto bg-white rounded-3xl m-5 max-w-4xl flex items-center flex-col md:flex-row truncate">
        <div class="flex-1 mt-7 md:mt-0">
            <h1 class="text-5xl font-extralight text-center">URL Shortener</h1>
        </div>
        <div class="flex-1">
            <form @submit.prevent="shortenUrl" class="my-6 mx-0 md:mx-6 flex flex-col items-start h-56 justify-around">

                <div class="w-full flex flex-row justify-start">
                    <div class="relative">
                        <input type="text" id="longUrl" v-model="longUrl" required placeholder="Enter an URL"
                            class="w-80 appearance-none border rounded py-2 px-3 text-gray-700 placeholder:text-gray-600 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-white border-gray-600 h-14"
                            :class="{ 'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-white': !isMessageError, 'focus:outline-none focus:ring-2 focus:ring-red-500 border-red-500 text-red-500 ': isMessageError }">
                        <div v-if="message" class="absolute -bottom-5">
                            <span v-if="isMessageError" class="text-sm text-red-500 font-bold">Error : </span>
                            <span v-else class="text-sm text-green-500">Shortened URL : </span>
                            <span v-if="isMessageError" class="text-sm text-red-500">{{ message }}</span>
                            <span v-else><a :href="message" target="_blank" class="text-sm text-blue-500 hover:underline">{{ message }}</a></span>
                        </div>
                    </div>
                </div>

                <div class="w-full flex flex-row justify-start">
                    <input type="text" id="folder" v-model="folder" placeholder="Enter a folder (Optional)"
                        class="w-80 appearance-none border rounded py-2 px-3 text-gray-700 placeholder:text-gray-600 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-white border-gray-600 h-14"
                        :class="{ 'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-white': !isMessageError, 'focus:outline-none focus:ring-2 focus:ring-red-500 border-red-500 text-red-500 ': isMessageError }">
                </div>

                <div>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-3.5 px-4 rounded focus:ring-blue-700">Shorten</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import axios from 'axios';

export default {
    data() {
        return {
            longUrl: '',
            folder: undefined,
            isMessageError: undefined,
            message: undefined,
        } as { longUrl: string; folder: string | undefined; isMessageError: boolean | undefined; message: string | undefined };;
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
