<template>
    <div>
        <form @submit.prevent="submitUrl">
            <input v-model="urlToShorten" placeholder="Enter URL to shorten" required>
            <button type="submit">Shorten</button>
        </form>
        <div v-if="shortenedUrl">
            Short URL: <a href="#" @click.prevent="redirectToOriginalUrl">{{ shortenedUrl }}</a>
        </div>
        <div v-if="errorMessage">{{ errorMessage }}</div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            urlToShorten: '',
            shortenedUrl: null,
            errorMessage: null
        };
    },
    methods: {
        async submitUrl() {
            try {
                const response = await axios.post('http://localhost:8000/api/shorten', { url: this.urlToShorten });
                this.shortenedUrl = response.data.short_url;
                this.errorMessage = null;
            } catch (error) {
                if (error.response && error.response.data.error) {
                    this.errorMessage = error.response.data.error;
                } else {
                    this.errorMessage = 'There was an unexpected error shortening the URL.';
                }
            }
        },
        async redirectToOriginalUrl() {
            try {
                const code = this.shortenedUrl.split('/').slice(-1)[0];
                const response = await axios.get(`http://localhost:8000/api/${code}`);
                window.open(response.data.redirect_url, '_blank');
            } catch (error) {
                if (error.response && error.response.data.error) {
                    this.errorMessage = error.response.data.error;
                } else {
                    this.errorMessage = 'There was an unexpected error shortening the URL.';
                }
            }
        }

    }
};
</script>

<style scoped>
div {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background-color: #f4f7f6;
    font-family: 'Arial', sans-serif;
}

form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

input {
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

input:focus {
    outline: none;
    border-color: #007bff;
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: #ffffff;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

div a {
    color: #007bff;
    text-decoration: none;
}

div a:hover {
    text-decoration: underline;
}

div[role="alert"] {
    color: #b90000;
}

.shortened-url,
.error-message {
    margin-top: 20px;
    text-align: center;
    font-size: 16px;
}

.shortened-url a {
    font-weight: bold;
}
</style>