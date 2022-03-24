<template>
  <div>
    <v-btn @click="hasHistory()
    ? $router.go(-1)
    : $router.push('/')" depressed color="primary" class="link-btn back-btn">
      Back
    </v-btn>
    <v-card v-if="post">
      <v-responsive>
        <v-card-text>
          <b>{{ post.title }}</b><br><br>
          <div v-html="post.description"></div>
        </v-card-text>
      </v-responsive>
      <v-divider></v-divider>
      <div class="create-comment">
        <div class="comment-title-text">Leave a comment</div>
        <v-divider></v-divider>
        <template>
          <v-container fluid>
            <v-textarea clearable clear-icon="mdi-close-circle" label="Your comment"
                        v-model="comments.content"></v-textarea>
          </v-container>
          <input hidden v-model="post.id"/>
          <v-btn class="comment-btn" depressed @click="storeComment()">
            Add comment
          </v-btn>
        </template>
        <br><br>
        <v-divider></v-divider>
        <div class="comment-title-text">Display comment</div>
        <FetchComments :comments="post.comments"/>
      </div>
    </v-card>
  </div>
</template>

<script>
import FetchComments from '../comments/FetchComments.vue';
import PostService from "../../../service/PostService";
import CommentService from "../../../service/CommentService";

export default {
  components: {
    FetchComments
  },
  data() {
    return {
      post: [],
      message: null,
      errors: [],
      comments: []
    }
  },
  mounted() {
    this.getPost(this.$route.params.id);
  },
  methods: {
    getPost(id) {
      PostService.show(id).then(response => {
        this.post = response.data.data;
      });
    },
    storeComment() {
      const data = {
        id: this.post.id,
        type: 'post',
        content: this.comments.content,
      };
      CommentService.store(data).then(response => {
        response.data;
        this.comments.content = '';
        this.getPost(this.$route.params.id);
        this.message = 'The comment was stored success!';
        this.$toast.success(this.message, {
          position: "top-right",
          timeout: 5000,
          closeOnClick: true,
          pauseOnFocusLoss: true,
          pauseOnHover: true,
          draggable: true,
          draggablePercent: 0.6,
          showCloseButtonOnHover: false,
          hideProgressBar: false,
          closeButton: "button",
          icon: true,
          rtl: false
        });
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors.content[0], {
            position: "top-right",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false
          });
        }
      });
    },
    hasHistory() {
      return window.history.length > 2;
    }
  }
}
</script>

<style scoped lang="scss">
.link-btn {
  text-decoration: none;
  color: inherit;
}

.back-btn {
  margin-bottom: 10px;
}

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
