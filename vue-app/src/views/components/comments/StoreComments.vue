<template>
  <div class="create-comment">
    <div class="comment-title-text">Leave a comment</div>
    <v-divider></v-divider>
    <template>
      <v-container fluid>
        <v-textarea clearable clear-icon="mdi-close-circle" label="Your comment" v-model="comment.content"></v-textarea>
      </v-container>
      <input hidden v-model="id" />
      <input hidden v-model="type" />
      <v-alert v-if="message" dense text type="success">{{ message }}</v-alert>
      <v-alert dense text type="error" v-if="errors.content">
        {{ errors.content[0] }}
      </v-alert>
      <v-btn class="comment-btn" depressed @click="storeComment()">
        Add comment
      </v-btn>
    </template>
    <br><br>
    <v-divider></v-divider>
    <div class="comment-title-text">Display comment</div>
  </div>
</template>

<script>
  import Comment from "@/service/Comment";

  export default {
    props: {
      id: [],
      type: []
    },
    data() {
      return {
        comment: [],
        message: "",
        errors: []
      }
    },
    methods: {
      storeComment() {
        const data = {
          id: this.id,
          type: this.type,
          content: this.comment.content,
        };
        Comment.store(data).then(response => {
          console.log(response.data)
          this.message = 'The comment was stored successfully!'
          this.comment.content = ''
        }).catch(e => {
          console.log(e);
          if (e.response.status === 422) {
            this.errors = e.response.data.errors
          }
        });
      }
    }
  }
</script>

<style scoped lang="scss">
  .comment-title-text {
    font-size: 25px;
    margin-left: 10px;
  }

  .comment-btn {
    margin-left: 10px;
  }

  .comment-btn v-btn {
    margin-bottom: 10px;
  }
</style>