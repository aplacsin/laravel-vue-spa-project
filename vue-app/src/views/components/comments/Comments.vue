<template>
  <div class="create-comment">
    <div class="comment-title-text">Leave a comment</div>
    <v-divider></v-divider>
    <template>
      <v-container fluid>
        <v-textarea clearable clear-icon="mdi-close-circle" label="Your comment" v-model="comments.content"></v-textarea>
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
    <FetchComments :comments="comments" />
  </div>
</template>

<script>
  import CommentService from "@/service/CommentService";
  import FetchComments from '../comments/FetchComments.vue';

  export default {
    components: {
      FetchComments
    },
    props: {
      id: [],
      type: []
    },
    data() {
      return {
        comments: [],
        message: "",
        errors: [],            
      }
    }, 
    mounted() {
      this.getComments(this.$route.params.id)
    },
    methods: {
      getComments(id) {
        CommentService.show(id).then(response => {
          this.comments = response.data
        });
      },
      storeComment() {
        const data = {
          id: this.id,
          type: this.type,
          content: this.comments.content,
        };
        CommentService.store(data).then(response => {
          console.log(response.data)
          this.message = 'The comment was stored successfully!'
          this.comments.content = ''
          this.getComments(this.$route.params.id)
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