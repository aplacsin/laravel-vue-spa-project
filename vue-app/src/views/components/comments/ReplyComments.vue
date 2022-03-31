<template>
  <div class="reply-comment">
    <div>
      <ConfirmDlg ref="confirm"/>
    </div>
    <div class="user-comment" v-for="reply in comment.replies" :key="reply.id">
      <v-divider></v-divider>
      <div class="user-name-comment">
        <div><b>{{ reply.user.name }}</b> - {{ reply.created_at }}</div>
      </div>
      <div class="wrapper-content">
        <div> {{ reply.content }}</div>
      </div>
      <div class="d-flex">
        <div class="reply">
          <v-btn class="comment-btn" text @click="isShow = !isShow">
            Reply
          </v-btn>
        </div>
        <div>
        </div>
        <div v-if="user.id === reply.user_id">
          <EditComment :comments="reply.id" :getComment="getComment"/>
        </div>
        <div v-if="user">
          <v-btn v-if="user.id === reply.user_id"
                 class="comment-btn"
                 color="red darken-1"
                 text
                 @click="deleteComment(reply.id)">
            Delete
          </v-btn>
        </div>
      </div>
      <div v-show="isShow">
        <v-container fluid>
          <v-textarea clearable clear-icon="mdi-close-circle" label="Your comment" v-model="content">
          </v-textarea>
        </v-container>
        <v-btn class="comment-btn" depressed @click="storeComment(reply.id, content)">
          Send
        </v-btn>
      </div>
    </div>
  </div>
</template>

<script>
import ConfirmDlg from "@/views/components/dialogs/ConfirmDlg";
import EditComment from "@/views/components/comments/EditComment";

export default {
  components: {
    EditComment,
    ConfirmDlg
  },
  props: {
    comment: [],
    getComment: {
      type: Function
    },
    deleteComment: {
      type: Function
    },
    storeComment: {
      type: Function
    },
  },
  data() {
    return {
      user: [],
      isShow: false,
      content: [],
    }
  },
  mounted() {
    this.user = JSON.parse(window.localStorage.getItem('userData'))
  },
}
</script>

<style scoped lang="scss">

.user-name-comment {
  margin-top: 5px;
  margin-left: 15px;
  margin-right: 15px;
}

.wrapper-content {
  margin-top: 10px;
  margin-left: 15px;
  margin-right: 15px;
  white-space: -moz-pre-wrap !important;
  white-space: -o-pre-wrap;
  white-space: pre-wrap;
  word-wrap: break-word;
  word-break: break-all;
}

.comment-btn {
  font-size: 12px;
  margin-top: 15px;
  margin-bottom: 5px;
  margin-left: 15px;
}

.user-comment {
  margin-left: 30px;
}

</style>
