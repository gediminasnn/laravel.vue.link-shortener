<template>
    <input
      :value="modelValue"
      @input="onInput($event)"
      :placeholder="placeholder"
      :class="inputClass"
      class="w-80 appearance-none border rounded py-2 px-3 text-gray-700 placeholder:text-gray-600 leading-tight focus:outline-none focus:ring-2 focus:border-white border-gray-600 h-14"
    />
  </template>

  <script lang="ts">
  export default {
    props: {
      modelValue: {
        type: String,
        default: ''
      },
      placeholder: {
        type: String,
        default: ''
      },
      isMessageError: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      onInput(event: Event) {
        const input = event.target as HTMLInputElement;
        if (input) {
          this.$emit('update:modelValue', input.value);
        }
      }
    },
    computed: {
      inputClass(): Record<string, boolean> {
        return {
          'focus:ring-blue-500': !this.isMessageError,
          'ring-red-500 border-red-500 text-red-500 ': this.isMessageError
        };
      }
    }
  };
  </script>

  <style scoped>
  </style>
