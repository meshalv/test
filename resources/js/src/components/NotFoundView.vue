<template>
    <div class="not-found">
        <h1>404</h1>
        <p>{{ message }}</p>
    </div>
</template>

<script setup>
defineProps({
    message: { type: String, default: 'Not found' }
});
</script>

<style scoped>
.not-found {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
    font-size: 1.2rem;
    color: #555;
}
</style>
