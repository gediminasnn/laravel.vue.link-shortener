<template>
    <form @submit.prevent="shortenUrl" class="my-6 mx-0 md:mx-6 flex flex-col items-start h-56 justify-around">
        <div class="w-full flex flex-row justify-start">
            <div class="relative">
                <InputField id="longUrl" v-model="longUrl" required placeholder="Enter an URL" :isMessageError="isMessageError" />
                <div v-if="message" class="absolute -bottom-4">
                    <Message :message="message" :isError="isMessageError" />
                </div>
            </div>
        </div>
        <div class="w-full flex flex-row justify-start">
            <InputField id="folder" v-model="folder" placeholder="Enter a folder (Optional)" :isMessageError="isMessageError" />
        </div>
        <div>
            <SubmitButtonPrimary name="Shorten" />
        </div>
    </form>
</template>

<script lang="ts">
import axios from 'axios';
import InputField from '../common/InputField.vue';
import Message from './Message.vue';
import SubmitButtonPrimary from '../common/SubmitButtonPrimary.vue';

export default {
    components: {
        InputField,
        Message,
        SubmitButtonPrimary,
    },
    data() {
        return {
            longUrl: '',
            folder: undefined,
            isMessageError: undefined,
            message: undefined,
        } as {
            longUrl: string;
            folder: string | undefined;
            isMessageError: boolean | undefined;
            message: string | undefined
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
    }
};
</script>

<style scoped></style>
