<template>
<section>
  <div v-if="isRegistering" class="box register-form">
    <div v-if="!isRegistered">
      <h3 class="title has-text-centered">Un momento <span class="emoji">⌛</span></h3>
      <p class="subtitle has-text-centered">This shouldn’t take long</p>
    </div>
    <div v-else>
      <h3 class="title has-text-centered">Congratulazioni <span class="emoji">👍</span></h3>
      <p class="subtitle has-text-centered">Your account has been created</p>
    </div>
    <div class="circle-loader" :class="isRegistered? 'load-complete' : ''">
      <div v-if="isRegistered" class="checkmark draw"></div>
    </div>
    <div class="has-text-centered" v-if="isRegistered">
      <b-button tag="a" href="/" icon-right="arrow-right">Start learning</b-button>
    </div>
  </div>
  <form ref="form" v-else class="box register-form">
    <h3 class="title has-text-centered">Ti diamo il benvenuto <span class="emoji">👋</span></h3>
    <p class="subtitle has-text-centered">Enter your details below to begin</p>
    <b-notification
          v-if="errors.course"
          type="is-danger"
          has-icon
          role="alert"
          :closable="false"
          :message="errors.course[0]">
    </b-notification>
    <b-steps size="is-small" mobile-mode="compact" v-model="activeStep" :animated="true" :has-navigation="false">

      <b-step-item label="Account" :clickable="true">

        <b-field label="First name" :message="errors.first_name" :type="errors.first_name ? 'is-danger' : null">
          <b-input required name="first_name" v-model="user.first_name" ></b-input>
        </b-field>

        <b-field label="Last name" :message="errors.last_name" :type="errors.last_name ? 'is-danger' : null">
          <b-input required name="last_name" v-model="user.last_name"></b-input>
        </b-field>

        <b-field label="Email" :message="errors.email" :type="errors.email ? 'is-danger' : null">
          <b-input required type="email" name="email" v-model="user.email"></b-input>
        </b-field>

        <b-field label="Password" :message="errors.password || 'Minimum 8 characters'" :type="errors.password ? 'is-danger' : null">
          <b-input v-model="user.password" name="password" type="password" password-reveal minlength="8" required>
          </b-input>
        </b-field>

        <hr>

        <button class="button is-fullwidth" @click.prevent="activeStep = 1">Next</button>

      </b-step-item>

      <b-step-item label="Introduce" :clickable="true">

        <b-field label="Introduce yourself">
          <b-input v-model="user.description" name="description" type="textarea" maxlength="120" rows="6" required placeholder="Introduce yourself to the other students.">
          </b-input>
        </b-field>

        <button class="button is-fullwidth" @click.prevent="activeStep = 2">Next</button>

      </b-step-item>

      <b-step-item label="Photo" :clickable="true">
        <b-field label="Photo">
          <camera-field mode="photo" v-if="activeStep == 2" v-model="user.photo"></camera-field>
        </b-field>
        <hr>
        <b-button :disabled="!user.photo" type="is-primary" @click.prevent="onSubmit" :loading="isRegistering" expanded>Register</b-button>
      </b-step-item>
    </b-steps>
  </form>
</section>
</template>

<script>
export default {
  props: ['course','admin'],
  data() {
    return {
      activeStep: 0,
      isRegistering: false,
      isRegistered: false,
      errors: '',
      user: {
        first_name: null,
        last_name: null,
        email: null,
        password: null,
        description: null,
        photo: null,
      }
    }
  },
  mounted() {
  },
  computed: {

  },
  methods: {

    onSubmit() {
      this.isRegistering = true;
      const data = new FormData();

      for (let [key, value] of Object.entries(this.user)) {
        if(value) {
          data.append(key,value);
        }
      }

      if(this.course) {
        data.append('course', this.course);
      }
      if(this.admin) {
        data.append('admin', this.admin);
      }

      axios.post('/register', data, {
         headers: {'Content-Type': `multipart/form-data; boundary=${data._boundary}`},
         timeout: 10000
      })
        .then(response => {
          setTimeout(
            () => {
              this.isRegistered = true;
            }, 1000
          )
        })
        .catch(error => {
          this.isRegistering = false;
          this.activeStep = 0;
          this.$buefy.snackbar.open({
                  message: `<b>Error:</b> ${error.response.data.message}`,
                  type: 'is-danger',
                  position: 'is-bottom',
                  duration: 5000
                });
          this.errors = error.response.data.errors || '';
        });
    }
  }
}
</script>

<style>
nav.steps {
  padding: 1em 0;
}
</style>
