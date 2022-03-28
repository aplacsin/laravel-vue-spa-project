<template>
  <div class="reply-comment" v-if=" comments && comments.length > 0">
    <div>
      <ConfirmDlg ref="confirm"/>
    </div>
    <div class="user-comment" v-for="comment in comments" :key="comment.id">
      <v-divider></v-divider>
      <div class="user-name-comment">
        <div><b>{{ comment.user.name }}</b> - {{ comment.created_at }}</div>
      </div>
      <div class="wrapper-content">
        <div> {{ comment.content }}</div>
      </div>
      <div class="d-flex">
        <div class="reply">
          <v-btn class="comment-btn" depressed>
            Reply
          </v-btn>
        </div>
        <div>
        </div>
        <div v-if="user">
          <v-btn v-if="user.id === comment.user_id"
                 class="comment-btn"
                 color="red darken-1"
                 text
                 @click="delComment(comment.id)">
            Delete
          </v-btn>
        </div>
      </div>
    </div>
  </div>
  <div v-else>
    <div class="text-center">
      No comments found.
    </div>
  </div>
</template>

<script>

import CommentService from "@/service/CommentService";
import ConfirmDlg from "@/views/components/dialogs/ConfirmDlg";

export default {
  components: {
    ConfirmDlg
  },
  props: {
    comments: [],
    getComment: {
      type: Function
    },
  },
  data() {
    return {
      user: [],
      isLoggedIn: true
    }
  },
  mounted() {
    this.user = JSON.parse(window.localStorage.getItem('userData'))
  },
  methods: {
    async delComment(id) {
      if (
          await this.$refs.confirm.open(
              "Confirm",
              "Are you sure you want to delete this comment?"
          )
      ) {
        this.deleteComment(id);
      }
    },
    deleteComment(id) {
      CommentService.delete(id).then(response => {
        response.data;
        this.getComment(this.$route.params.id);
        this.message = 'The comment was deleted success!';
        this.$toast.success(this.message);
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors);
        }
      });
    }
  }
}
</script>

<style scoped lang="scss">

.user-name-comment {
  margin-left: 15px;
}

.wrapper-content {
  margin-left: 15px;
  white-space: -moz-pre-wrap !important;
  white-space: -o-pre-wrap;
  white-space: pre-wrap;
  word-wrap: break-word;
  word-break: break-all;
}

.comment-btn {
  margin-bottom: 5px;
  margin-left: 15px;
}

</style>
