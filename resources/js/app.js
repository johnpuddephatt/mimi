require('./bootstrap');

import Vue from 'vue';
// import '@mdi/font/css/materialdesignicons.css' // <-- Just here
import Buefy from 'buefy'
Vue.use(Buefy)

Vue.component('video-recorder', require('./components/Recorder.vue').default);
Vue.component('video-player', require('./components/VideoPlayer.vue').default);
Vue.component('video-playlist', require('./components/Playlist.vue').default);
Vue.component('registration-form', require('./components/Registration.vue').default);
Vue.component('lesson-form', require('./components/Lesson.vue').default);

const app = new Vue({
    el: '#app',
});


// Bulma NavBar Burger Script
document.addEventListener('DOMContentLoaded', function () {
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    if ($navbarBurgers.length > 0) {
        $navbarBurgers.forEach(function ($el) {
            $el.addEventListener('click', function () {
                let target = $el.dataset.target;
                let $target = document.getElementById(target);
                $el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
    }
});
