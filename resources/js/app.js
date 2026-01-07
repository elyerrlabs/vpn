import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { $notify } from "@vpn/config/notify.js";
import { $server } from "@vpn/config/axios.js";
import { setupI18n, __ } from "@vpn/config/locale.js";

setupI18n();
window.__ = __;
window.$server = $server
window.$notify = $notify

createInertiaApp({
  resolve: (name) => require(`./pages/${name}.vue`).default,
  setup({ el, App, props, plugin }) {

    const app = createApp({ render: () => h(App, props) });

    app.config.globalProperties.__ = __;

    app.use(plugin);
    app.mount(el);
  },
});
