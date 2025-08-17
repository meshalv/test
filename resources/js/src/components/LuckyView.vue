<template>
    <div class="container">
        <div v-if="message" class="alert alert-success">{{ message }}</div>
        <div v-if="error" class="alert alert-error">{{ error }}</div>

        <div v-if="isActive">
            <h1 class="title">Hello, {{ username }}</h1>
            <p class="info">Link expired at: {{ expired_at }}</p>

            <div class="buttons">
                <button class="btn" @click="regenerate">Generate New Link</button>
                <button class="btn btn-secondary" @click="deactivate">Deactivate Link</button>
                <button class="btn btn-secondary" @click="loadHistory">History</button>
                <button class="btn btn-primary" @click="feelingLucky">I'm feeling lucky</button>
            </div>

            <div v-if="history && history.length">
                <h2 class="history-title">History (last 3)</h2>
                <table class="history-table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Result</th>
                        <th>Win</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in history" :key="item.created_at || item.number">
                        <td>{{ item.created_at }}</td>
                        <td>{{ item.number }}</td>
                        <td>{{ item.result }}</td>
                        <td>{{ item.amount }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['token'],
    data() {
        return {
            username: '',
            expired_at: '',
            isActive: true,
            history: [],
            message: null,
            error: null,
        };
    },
    async mounted() {
        await this.init();
    },
    methods: {
        getPayload(res) {
            return res.data?.data ?? res.data ?? null;
        },

        async init() {
            try {
                const res = await axios.get(`/api/lucky/${this.token}`);
                const data = this.getPayload(res);
                this.username = data.username;
                this.expired_at = data.expired_at;
                this.isActive = true;
            } catch (error) {
                this.handleError(error);
            }
        },

        async regenerate() {
            try {
                const res = await axios.post(`/api/lucky/${this.token}/regenerate`);
                const data = this.getPayload(res);
                this.expired_at = data.expired_at;
                this.isActive = true;
                this.message = 'New link generated!';
                this.error = null;
                this.$router.replace({ path: `/lucky/${data.token}` });
            } catch (error) {
                this.handleError(error);
            }
        },

        async deactivate() {
            try {
                const res = await axios.post(`/api/lucky/${this.token}/deactivate`);
                const data = this.getPayload(res);
                this.isActive = false;
                this.message = data.message || 'Link deactivated';
                this.error = null;
            } catch (error) {
                this.handleError(error);
            }
        },

        async feelingLucky() {
            try {
                const res = await axios.post(`/api/lucky/${this.token}/lucky`);
                const data = this.getPayload(res);
                this.message = `NUMBER: ${data.number}, RESULT: ${data.result}, WIN: ${data.amount}`;
                this.error = null;
                if (this.history.length) {
                    this.loadHistory();
                }
            } catch (error) {
                this.handleError(error);
            }
        },

        async loadHistory() {
            try {
                const res = await axios.get(`/api/lucky/${this.token}/history`);
                const data = this.getPayload(res);
                this.history = data.items ?? [];
            } catch (error) {
                this.handleError(error);
            }
        },

        handleError(error) {
            this.message = null;
            const status = error.response?.status;
            const msg = error.response?.data?.message || "Response error";

            if (status === 404) {
                this.$router.replace('/404');
            } else if (status === 410) {
                this.isActive = false;
                this.error = "Link is deactivated or expired";
            } else {
                this.error = msg;
            }
        },
    },
};
</script>

<style scoped>
.container {
    max-width: 500px;
    height: 470px;
    margin: 2rem auto;
    padding: 1.5rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    overflow-y: auto;
}
.title {
    margin-bottom: 0.5rem;
    font-size: 2rem;
    color: #4caf50;
}

.info {
    margin-bottom: 1rem;
    color: #555;
}

.alert {
    padding: 12px;
    margin-bottom: 16px;
    border-radius: 6px;
    font-weight: 500;
}

.alert-success {
    background-color: #e6ffed;
    color: #2f855a;
    border: 1px solid #9ae6b4;
}

.alert-error {
    background-color: #ffe6e6;
    color: #c53030;
    border: 1px solid #fc8181;
}

.buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin: 1rem 0;
}

.btn {
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

.btn-secondary {
    background-color: #3182ce;
}

.btn-secondary:hover {
    background-color: #2b6cb0;
}

.btn-primary {
    background-color: #d69e2e;
}

.btn-primary:hover {
    background-color: #b7791f;
}

.history-title {
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
    color: #4caf50;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.history-table th,
.history-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}

.history-table th {
    background-color: #f2f2f2;
}
</style>
