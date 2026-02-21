import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // css
                'resources/scss/app.scss',
                'resources/scss/bootstrap.scss',
                'resources/scss/icons.scss',
                'node_modules/swiper/swiper-bundle.min.css',
                'node_modules/magnific-popup/dist/magnific-popup.css',
                'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
                'node_modules/leaflet/dist/leaflet.css',
                'node_modules/jarallax/dist/jarallax.css',
                'node_modules/simple-datatables/dist/style.css',
                'node_modules/jsvectormap/dist/jsvectormap.min.css',
                'node_modules/vanilla-datatables-editable/datatable.editable.min.css',
                'node_modules/vanilla-datatables/dist/vanilla-dataTables.min.css',
                'node_modules/tui-chart/dist/tui-chart.min.css',
                'node_modules/magic.css/dist/magic.min.css',
                'node_modules/dragula/dist/dragula.min.css',
                'node_modules/nouislider/dist/nouislider.min.css',
                'node_modules/starability/starability-css/starability-all.css',
                'node_modules/animate.css/animate.min.css',
                'node_modules/sweetalert2/dist/sweetalert2.min.css',
                'node_modules/mobius1-selectr/dist/selectr.min.css',
                'node_modules/huebee/dist/huebee.min.css',
                'node_modules/vanillajs-datepicker/dist/css/datepicker.min.css',
                'node_modules/quill/dist/quill.snow.css',
                'node_modules/uppy/dist/uppy.min.css',
                'node_modules/@uppy/core/dist/style.min.css',
                'node_modules/@uppy/dashboard/dist/style.min.css',
                'node_modules/cropperjs/dist/cropper.min.css',
                'node_modules/tobii/dist/css/tobii.min.css',
                'node_modules/listree/dist/listree.min.css',

                // js
                'resources/js/app.js',
                'resources/js/DynamicSelect.js',
                'resources/js/pages/index.init.js',
                'resources/js/pages/payment.init.js',
                'resources/js/pages/transactions.init.js',
                'resources/js/pages/file-upload.init.js',
                'resources/js/pages/datatable.init.js',
                'resources/js/pages/calendar.init.js',
                'resources/js/pages/gmaps.init.js',
                'resources/js/pages/vectormap.init.js',
                'resources/js/pages/leaflet-map.init.js',
                'resources/js/pages/apexcharts.init.js',
                'resources/js/pages/justgage.init.js',
                'resources/js/pages/chartjs.init.js',
                'resources/js/pages/toast-ui.init.js',
                'resources/js/pages/animation.init.js',
                'resources/js/pages/clipboard.init.js',
                'resources/js/pages/dragula.init.js',
                'resources/js/pages/ui-slider.js',
                'resources/js/pages/sweet-alert.init.js',
                'resources/js/pages/toast.init.js',
                'resources/js/pages/forms-advanced.js',
                'resources/js/pages/form-validation.js',
                'resources/js/pages/form-wizard.js',
                'resources/js/pages/form-editor.init.js',
                'resources/js/pages/file-upload.init.js',
                'resources/js/pages/crop.init.js',
                'resources/js/pages/profile.init.js',
                'resources/js/pages/tree.init.js'
            ],
            refresh: true,
        }),
    ],
    define: {
        global: 'window'
    }
});
