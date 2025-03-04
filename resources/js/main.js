import App from '@/App.vue'
import { registerPlugins } from '@core/utils/plugins'
import { computed, createApp } from 'vue'
import sweetalert from './composables/sweetalert'
// Styles
import '@core-scss/template/index.scss'
import '@styles/styles.scss'
import "sweetalert2/dist/sweetalert2.min.css"

//sweet alert
import Vue3Toastify from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'


async function bootstrap() {
    // Create Vue app first
    const app = createApp(App)
    registerPlugins(app)

    

    app.use(sweetalert)
    // Use Vue3Toastify correctly
    app.use(Vue3Toastify, {
        autoClose: 3000,
    });

    // Now we can use the store
    const store = app.config.globalProperties.$store;

    
    if (!store) {
        console.error("Store is not available.");
        return;
    }

    const isLoggedIn = computed(() => store.getters["auth/isLoggedIn"]);

    if (isLoggedIn.value) {
        store.dispatch("auth/updateUser");
    }

    // Mount Vue app
    app.mount('#app')
}

bootstrap();
