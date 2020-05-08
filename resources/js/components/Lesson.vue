<template>
  <div v-if="isSaving" class="box register-form">
    <div v-if="isSaved">
      <h3 class="title has-text-centered">Ottime <span class="emoji">✨</span></h3>
      <p class="subtitle has-text-centered">Your lesson has been {{ data ? 'updated' : 'created' }}</p>
    </div>
    <div v-else>
      <h3 class="title has-text-centered">Un momento <span class="emoji">⌛</span></h3>
      <p class="subtitle has-text-centered">Your lesson is uploading</p>
    </div>

    <div class="circle-loader" :class="isSaved? 'load-complete' : ''">
      <div v-if="isSaved" class="checkmark draw"></div>
    </div>
    <div class="has-text-centered" v-if="isSaved">
      <b-button tag="a" href="/" icon-right="arrow-right">View this lesson</b-button>
    </div>
    <div class="has-text-centered" v-else>
      {{ uploadProgress }}% uploaded
    </div>
  </div>
  <div v-else class="box">
    <h3 class="title has-text-centered">{{ data ? 'Modifica' : 'Creare' }} lezione <span class="emoji">🆕</span></h3>
    <p class="subtitle has-text-centered">{{ data ? 'Edit the' : 'Set up a new'  }} lesson below</p>
    <b-notification
          v-if="errors.course"
          type="is-danger"
          has-icon
          role="alert"
          :closable="false"
          :message="errors.course[0]">
    </b-notification>

    <b-field label="Title" :message="errors.title" :type="errors.title ? 'is-danger' : null">
      <b-input required name="title" v-model="lesson.title" placeholder="Enter the title for this lesson"></b-input>
    </b-field>

    <b-field label="Instructions" :message="errors.instructions" :type="errors.instructions ? 'is-danger' : null">
      <b-input v-model="lesson.instructions" name="instructions" type="textarea" maxlength="120" rows="3" placeholder="Give instructions for this lesson">
      </b-input>
    </b-field>

    <b-field label="Video">
      <camera-field mode="video" v-model="lesson.video"></camera-field>
    </b-field>

    <b-field label="Number" message="Number is preset based on the number of lessons already added">
      <b-numberinput type="is-light" v-model="lesson.number" :editable="false"></b-numberinput>
    </b-field>

    <hr>
    <b-button type="is-primary" :disabled="!lesson.video" @click.prevent="onSubmit" :loading="isSaving" expanded>{{ data ? 'Update' : 'Create' }}</b-button>
  </div>
</template>

<script>
import CameraField from "./CameraField";

export default {
  props: ['data', 'course_id', 'lesson_count'],
  components: {
    'camera-field': CameraField,
  },
  data() {
    return {
      isSaving: false,
      isSaved: false,
      uploadProgress: null,
      errors: '',
      lesson: {
        id: null,
        title: null,
        instructions: null,
        video: null,
        number: this.lesson_count + 1,
      }
    }
  },

  mounted() {
    if(this.data) {
     this.lesson = this.data;
    }
  },

  methods: {

    onSubmit() {
      this.isSaving = true;

      const data = new FormData();

      for (let [key, value] of Object.entries(this.lesson)) {
        if(value) {
          data.append(key,value);
        }
      }

      if(this.course_id) {
        data.append('course_id', this.course_id);
      }

      data.append('_method', (this.data ? 'PUT' : 'POST'));

      axios({
        method: 'post',
        url: (this.data ? `/admin/course/${ this.data.course_id }/lesson/${ this.data.id }` : `/admin/course/${ this.course_id }/lesson/create`),
        data: data,
        headers: {'Content-Type': `multipart/form-data; boundary=${data._boundary}`},
        onUploadProgress: progressEvent => this.updateProgress(Math.round( (progressEvent.loaded * 100) / progressEvent.total )),
        timeout: 30000
      })
        .then(response => {
          this.lesson.id = response.data.id;
          setTimeout(
            () => {
              this.isSaved = true;
            }, 1000
          )
        })
        .catch(error => {
          this.isSaving = false;
          this.$buefy.snackbar.open({
                  message: `<strong>Error:</strong> ${error.response.data.message}`,
                  type: 'is-danger',
                  position: 'is-bottom',
                  duration: 5000
                });
          this.errors = error.response.data.errors || '';
        });
    },
    updateProgress(progress) {
      this.uploadProgress = progress;
    }
  }
}
</script>

<style>
</style>
