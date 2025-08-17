<template>
    <div class="container">
        <h1 class="title">Register</h1>
        <form @submit.prevent="submit" class="form">
            <div class="form-group">
                <input v-model="username" placeholder="Username" required />
            </div>
            <div class="form-group">
                <input v-model="phone_number" placeholder="Phone Number" required />
            </div>
            <button type="submit" class="btn">Register</button>
        </form>

        <div v-if="link" class="link-box">
            <p>Your unique link (expired at: {{ expired_at }}):</p>
            <a :href="link" target="_blank" rel="noopener noreferrer">{{ link }}</a>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            username: '',
            phone_number: '',
            link: null,
            expired_at: null,
        };
    },
    methods: {
        async submit() {
            const res = await axios.post('/api/registration', {
                username: this.username,
                phone_number: this.phone_number,
            });
            this.link = res.data.data.link;
            this.expired_at = res.data.data.expired_at;
        },
    },
};
</script>

<style scoped>
.container {
    max-width: 400px;
    margin: 2rem auto;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    word-break: break-word;
}

.title {
    text-align: center;
    color: #4caf50;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.btn {
    width: 100%;
    padding: 0.5rem 1rem;
    border: none;
    background-color: #4caf50;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}

.btn:hover {
    background-color: #45a049;
}

.link-box {
    margin-top: 1rem;
    padding: 0.5rem;
    border: 1px dashed #aaa;
    border-radius: 4px;
    word-break: break-word;
    overflow-wrap: anywhere;
}
</style>
