<script setup>
    defineProps(['modelValue', 'placeholder', 'options', 'optionKey', 'optionValue']);
    defineEmits(['update:modelValue']);
</script>

<template>
    <select
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    >
        <option value="">{{ placeholder }}</option>
        <template v-for="option in options">
            <option :value="option[optionKey]">
                {{ option[optionValue] }}
            </option>
        </template>
    </select>
</template>
