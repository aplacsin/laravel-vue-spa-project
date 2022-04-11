<template>
  <div class="reply-comment">
    <div>
      <ConfirmDlg ref="confirm"/>
    </div>
    <div v-for="reply in comment.replies" :key="reply.id">
      <div class="wrapper-comment">
        <v-divider></v-divider>
        <div class="wrapper-comment-content">
          <div class="wrapper-comment-avatar">
            <div class="user-avatar">
              <img :src="image" alt="download.jpeg"/>
            </div>
          </div>
          <div class="wrapper-content">
            <div class="user-name-comment">
              <div><b>{{ reply.user.name }}</b> - {{ reply.created_at }}</div>
            </div>
            <div class="comment-content">
              <div> {{ reply.content }}</div>
            </div>
            <div class="d-flex">
              <div class="reply">
                <v-btn
                    class="comment-btn"
                    text
                    @click="isShow = !isShow"
                >
                  Reply
                </v-btn>
              </div>
              <div>
              </div>
              <div v-if="user.id === reply.user_id">
                <EditComment
                    :comments="reply.id"
                    :getComment="getComment"
                />
              </div>
              <div v-if="user">
                <v-btn
                    class="comment-btn"
                    color="red darken-1"
                    text
                    v-if="user.id === reply.user_id"
                    @click="deleteComment(reply.id)"
                >
                  Delete
                </v-btn>
              </div>
            </div>
          </div>
        </div>
        <div v-show="isShow">
          <v-container fluid>
            <v-textarea
                outlined
                clearable
                clear-icon="mdi-close-circle"
                label="Your comment"
                v-model="content"
            ></v-textarea>
          </v-container>
          <v-btn
              class="comment-btn"
              depressed
              @click="storeComment(reply.id, content)"
          >
            Send
          </v-btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ConfirmDlg from "@/components/ui/dialogs/ConfirmDlg";
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
      image: '/img/download.jpeg'
    }
  },
  mounted() {
    this.user = JSON.parse(window.localStorage.getItem('userData'))
  },
}
</script>

<style scoped lang="scss">

.wrapper-comment {
  margin-left: 50px;
}

.wrapper-comment-content {
  display: flex;
}

.wrapper-comment-avatar {
  max-width: 80px;
  max-height: 80px;
  margin-top: 10px;
  margin-left: 10px;
}

.user-avatar img {
  width: 80px;
  height: 80px;

  .user-avatar img {
    width: 100%;
  }
}

.user-name-comment {
  margin-top: 5px;
  margin-left: 15px;
  margin-right: 15px;
}

.comment-content {
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

</style>
