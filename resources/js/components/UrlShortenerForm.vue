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