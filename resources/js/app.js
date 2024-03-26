import './bootstrap';

import { createApp } from 'vue';
import UrlShortenerForm from "./components/UrlShortenerForm.vue"

const app = createApp({
    components: {
        UrlShortenerForm
    }
});

app.mount("#app");