// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    devtools: {enabled: true},
    modules: [
        '@nuxtjs/tailwindcss',
    ],
    tailwindcss: {
        cssPath: './assets/css/custom.css',
        configPath: 'tailwind.config.js',
        exposeConfig: true,
    },
    css: [
        '~/assets/css/custom.css',
    ],
    postcss: {
        plugins: {
            '@tailwindcss/postcss': {},
            autoprefixer: {}
        }
    }
})
