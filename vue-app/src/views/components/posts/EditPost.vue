<template>
  <div v-if="post">
    <router-link :to="{ name: 'ListPosts' }" style="text-decoration: none; color: inherit;">
      <v-btn depressed color="primary" style="margin-bottom: 10px;">
        Back
      </v-btn>
    </router-link>
    <template>
      <v-container fluid>
        <v-textarea clearable clear-icon="mdi-close-circle" label="Title" v-model="post.title"></v-textarea>
        <v-textarea clearable clear-icon="mdi-close-circle" label="Description" v-model="post.description"></v-textarea>
        <v-alert v-if="message" dense text type="success">{{ message }}</v-alert>
      </v-container>
    </template>
    <v-btn depressed @click="updatePost(post.id)">
      Save
    </v-btn>

  </div>


</template>

<script>
import PostService from "@/service/PostService";

export default {
  data() {
    return {
      post: [],
      message: null
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
            this.message = 'The post was updated successfully!';
          })
          .catch(e => {
            console.log(e);
          });
    },
  }

}
</script>