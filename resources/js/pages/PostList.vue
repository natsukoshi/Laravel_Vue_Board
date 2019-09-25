<template>
  <div>
    <p>
      <Postform v-on:reloadPosts="fetchPosts" />
    </p>
    <div class="postRaw" v-for="post in posts" :key="post.id">
      <router-link :to="`post/${ post.id }`">Title:{{ post.title }}</router-link>
      <br />
      Name:{{ post.user.name }}
      <br />
      <div class="img" v-if="post.image">
        <img v-bind:src="post.image.file_url" alt="投稿画像" />
      </div>
      <div class="space">{{ post.message }}</div>
      <div class="created_at">Time:{{ post.created_at }}</div>
      <button class="delete_button" v-if="post.user.id == userID" @click="deletePost(post.id)">削除</button>
    </div>
    <Pagination :currentPage="currentPage" :lastPage="lastPage" />
  </div>
</template>
<script>
import axios from "axios";
import Postform from "../components/Postform.vue";
import Pagination from "../components/Pagination.vue";
import { mapGetters } from "vuex";
import { POST_PAGE, INTERNAL_SERVER_ERROR, NOT_FOUND } from "../util";

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
      posts: [],
      messasgeContent: "",
      currentPage: 0,
      lastPage: 0
    };
  },
  methods: {
    //投稿を全て取得する
    async fetchPosts() {
      const response = await axios.get(`/api/posts/?page=${this.page}`);

      //to-do 投稿エラーだった場合の処理
      //   if (response.status !== OK) {
      //     this.$store.commit('error/setCode', response.status)
      //     return false
      //   }
      console.log("現在ページ：" + this.page);
      console.log("fetchPost呼ばれたよ");
      this.posts = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
      console.log(this.posts);
      console.log(response.data);
    },

    //フォームのメッセージを投稿する
    async postMessage() {
      const response = await axios.post("/api/posts", {
        message: this.messasgeContent
      });

      //to-do 投稿エラーだった場合の処理

      this.messasgeContent = "";
      this.fetchPosts();
    },

    // 投稿を削除する
    async deletePost(targetID) {
      console.log("削除開始");

      const response = await axios
        .delete(`/api/posts/${targetID}`)
        .catch(function(err) {
          return err.response || err;
        });

      // 取得エラー時の処理
      if (response.status === NOT_FOUND) {
        this.$router.push("/404");
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        this.$router.push("/500");
      }

      console.log("削除後リロード");
      this.tryMethod();
      this.fetchPosts();
      console.log("削除完了");
    },
    async tryMethod() {
      console.log("ｔｒｙメソッド");
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
  },
  watch: {
    errorCode: {
      //算出プロパティerrorCodeに変更があったときに
      handler(val) {
        //呼ばれるコールバック関数。valはerrorCode()によって新しく取得された値（変更後の値）
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
        }
      },
      immediate: true //インスタンス生成時にも実行する
    },
    $route: {
      async handler() {
        console.log("fetchPost呼ぶ前");
        await this.fetchPosts();
        this.$store.commit("error/setStatusCode", null);
      },
      immediate: true
    }
    // $route () {
    //      console.log('fetchPost呼ぶ前')
    //   this.$store.commit('error/setStatusCode', null)
    // }
  }
};
</script>
