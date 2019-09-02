<template>
    <div>
        <div class="postRaw" v-for="post in posts" :key="post.id">
             ID:{{ post.id }}「{{ post.message }}」
        </div>
        <form @submit.prevent="postMessage">
            <textarea v-model="messasgeContent"></textarea>
            <button>メッセージ送信</button>
        </form>
    </div>
</template>
<script>
import axios from 'axios';

export default {
//   components: {/* 中略 */},
  data () {
    return {
      posts: [],
      messasgeContent: ''
    }
  },
  methods: {
    async fetchPosts () {
      const response = await axios.get('/api/posts');

    //   if (response.status !== OK) {
    //     this.$store.commit('error/setCode', response.status)
    //     return false
    //   }

      this.posts = response.data.data;
    },
    async postMessage () {
        const response = await axios.post('/api/posts',{
            message: this.messasgeContent
        })

        //to-do 投稿エラーだった場合の処理
        this.messasgeContent = '';


        this.fetchPosts();
    }
  },
  watch: {
    $route: {
      async handler () {
        await this.fetchPosts()
      },
      immediate: true
    }
  }
}
</script>
