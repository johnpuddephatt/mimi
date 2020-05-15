require('./bootstrap');

import Vue from 'vue';
import Buefy from 'buefy'
import VueTimeago from 'vue-timeago'

Vue.use(Buefy);

Vue.use(VueTimeago, {
  name: 'timeago',
  locale: 'en',
  locales: {
    'en': require('date-fns/locale/en'),
  }
})

Vue.component('video-playlist', require('./components/Playlist.vue').default);
Vue.component('registration-form', require('./components/Registration.vue').default);
Vue.component('lesson-form', require('./components/Lesson.vue').default);
Vue.component('video-player', require('./components/VideoPlayer.vue').default);
Vue.component('create-reply', require('./components/CreateReply.vue').default);
Vue.component('reply-card', require('./components/ReplyCard.vue').default);
Vue.component('camera-field', require('./components/CameraField.vue').default);

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
