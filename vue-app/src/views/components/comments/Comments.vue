<template>
  <div class="create-comment">
    <div class="comment-title-text">Leave a comment</div>
    <v-divider></v-divider>
    <template>
      <v-container fluid>
        <v-textarea clearable clear-icon="mdi-close-circle" label="Your comment"
                    v-model="content"
                    @blur="updateVal($event.target.value)"
                    @keyup.enter="updateVal($event.target.value)">
        </v-textarea>
      </v-container>
      <input hidden v-model="id"/>
      <v-btn class="comment-btn" depressed @click="storeComment()">
        Add comment
      </v-btn>
    </template>
    <br><br>
    <v-divider></v-divider>
    <div class="comment-title-text">Display comment</div>
    <FetchComments :comments="comments" :getComment="getComment"/>
  </div>
</template>

<script>
import CommentService from "@/service/CommentService";
import FetchComments from "@/views/components/comments/FetchComments";

export default {
  components: {
    FetchComments
  },
  props: {
    id: [],
    type: [] ?? '',
    getComment: {
      type: Function
    },
    comments: [],
  },
  data() {
    return {
      content: [],
    }
  },
  methods: {
    updateVal(newVal) {
      this.edit = false;
      this.$emit('update:content', newVal);
    },
    storeComment() {
      const data = {
        id: this.id,
        type: this.type,
        content: this.content,
      };
      CommentService.store(data).then(response => {
        response.data;
        this.content = '';
        this.getComment(this.$route.params.id);
        this.message = 'The comment was stored success!';
        this.$toast.success(this.message);
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors.content[0]);
        }
      });
    },
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
