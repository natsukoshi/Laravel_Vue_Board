<template>
  <div>
    <router-link to="/">Topへ戻る</router-link>

    <div v-if="post" class="postRaw">
      Title:{{ post.title }}
      <br />
      Name:{{ post.user.name }}
      <br />
      <div class="img" v-if="post.image">
        <img v-bind:src="post.image.file_url" alt="投稿画像" />
      </div>
      Message:{{ post.message }}
      <br />
    </div>

    <div v-if="post && replies">
      <h2>返信</h2>
      <div class="postRaw" v-for="reply in replies" :key="reply.id">
        Title:{{ reply.title }}
        <br />
        Name:{{ reply.user.name }}
        <br />
        <div class="img" v-if="reply.image">
          <img v-bind:src="reply.image.file_url" alt="投稿画像" />
        </div>
        <div class="space">{{ reply.message }}</div>
        <div class="created_at">Time:{{ reply.created_at }}</div>
        <br />

        <button
          class="delete_button"
          v-if="reply.user.id == userID"
          @click="deleteReply(reply.id)"
        >削除</button>
      </div>
    </div>
    <Pagination :currentPage="currentPage" :lastPage="lastPage" />

    <h2>返信フォーム</h2>
    <Postform v-on:reloadPosts="fetchPost" />
  </div>
</template>

<script>
import axios from "axios";
import Postform from "../components/Postform.vue";
import Pagination from "../components/Pagination.vue";
import { mapGetters } from "vuex";
import { NOT_FOUND, INTERNAL_SERVER_ERROR } from "../util";

export default {
  components: {
    Postform,
    Pagination
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },

  data() {
    return {
      post: null,
      replies: null,
      messasgeContent: "",
      currentPage: 0,
      lastPage: 0
    };
  },

  methods: {
    //投稿と返信を取得する
    async fetchPost() {
      const response = await axios
        .get(`/api/posts/${this.$route.params.id}?page=${this.page}`)
        .catch(function(err) {
          return err.response || err;
        });

      // 取得エラー時の処理
      if (response.status === NOT_FOUND) {
        this.$router.push("/404");
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        this.$router.push("/500");
      }

      console.log(response);
      this.post = response.data.post;
      this.replies = response.data.replies.data;
      this.currentPage = response.data.replies.current_page;
      this.lastPage = response.data.replies.last_page;
      console.log(this.post);
      console.log(this.replies);
    },

    //返信を削除する
    async deleteReply(targetID) {
      const response = await axios
        .delete(`/api/reply/${targetID}`)
        .catch(function(err) {
          return err.response || err;
        });

      // 取得エラー時の処理
      if (response.status === NOT_FOUND) {
        this.$router.push("/404");
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        this.$router.push("/500");
      }

      this.fetchPost();

      console.log("削除完了");
    }
  },

  watch: {
    $route: {
      async handler() {
        await this.fetchPost();
        // console.log("ストアに値をセットした" + this.$route.params.id);
      },
      immediate: true
    }
  },
  computed: {
    //　store/authのステートを取得
    ...mapGetters("auth", [
      //ストア内のパスを指定
      "isLoggedin"
    ]),
    userID() {
      return this.$store.getters["auth/userID"];
    }
  }
};
</script>
