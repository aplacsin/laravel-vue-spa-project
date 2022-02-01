<template>
  <div v-if="post">
    <v-btn @click="hasHistory()
          ? $router.go(-1)
          : $router.push('/')"
           depressed color="primary" class="link-btn back-btn">
      Back
    </v-btn>
    <template>
      <v-container fluid>
        <v-text-field
            v-model="post.title"
            :counter="150"
            label="Title"
            required
        ></v-text-field>
        <vue2-tinymce-editor v-model="post.description" :options="options"></vue2-tinymce-editor>
        <v-alert v-if="message" dense text type="success" transition="slide-y-transition">{{ message }}</v-alert>
      </v-container>
    </template>
    <v-btn depressed @click="updatePost(post.id)">
      Save
    </v-btn>
  </div>
</template>

<script>
import PostService from "../../../service/PostService"
import { Vue2TinymceEditor } from "vue2-tinymce-editor"

export default {
  components: {
    Vue2TinymceEditor
  },
  data() {
    return {
      post: [],
      message: null,
      options: {
        menubar: false,
        plugins: 'autolink charmap code codesample directionality emoticons',
        toolbar1: 'fontselect | fontsizeselect | formatselect | bold italic underline strikethrough forecolor backcolor',
        toolbar2: 'alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | link table removeformat code',
      }
    }
  },
  mounted() {
    this.getEditPost(this.$route.params.id)
  },
  methods: {
    getEditPost(id) {
      PostService.edit(id).then(response => {
        this.post = response.data.data
      });
    },
    updatePost(id) {
      const data = {
        title: this.post.title,
        description: this.post.description
      };
      PostService.update(id, data)
          .then(response => {
            console.log(response.data);
            this.message = 'The post was updated successfully!'
            setTimeout(function(scope){
              scope.message = ''
            }, 2000, this)
          })
          .catch(e => {
            console.log(e)
          });
    },
    hasHistory() {
      return window.history.length > 2
    }
  }
}
</script>
