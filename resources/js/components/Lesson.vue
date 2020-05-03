<template>
  <div>
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


    <camera-field mode="video" @video="(video) => lesson.video = video"></camera-field>
    <hr>
    <b-button type="is-primary" @click.prevent="onSubmit" :loading="isSaving" expanded>Register</b-button>
  </div>
</template>

<script>
import CameraField from "./CameraField";

export default {
  props: ['course_id'],
  components: {
    'camera-field': CameraField,
  },
  data() {
    return {
      isSaving: false,
      isSaved: false,
      uploadProgress: 0,
      errors: '',
      lesson: {
        title: null,
        instructions: null,
        video: null,
      }
    }
  },
  mounted() {

  },
  computed: {

  },
  methods: {

    onSubmit() {

      const data = new FormData();

      for (let [key, value] of Object.entries(this.lesson)) {
        data.append(key,value);
      }

      data.append('course_id', this.course_id);

      axios.post('/admin/lesson/create', data, {
         headers: {'Content-Type': `multipart/form-data; boundary=${data._boundary}`},
         onUploadProgress: progressEvent => (this.uploadProgress  = Math.round( (progressEvent.loaded * 100) / progressEvent.total )));
         timeout: 30000
      })
        .then(response => {
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
    }
  }
}
</script>

<style>
</style>
