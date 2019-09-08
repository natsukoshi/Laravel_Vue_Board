<template>
    <div>
        <div class="postRaw" v-for="post in posts" :key="post.id">
             Name:{{ post.user.name }}「{{ post.message }}」
        </div>
        <form @submit.prevent="postMessage" v-if="isLoggedin">
            <textarea v-model="messasgeContent"></textarea>
            <button>メッセージ送信</button>
        </form>
    </div>
</template>
<script>
import axios from 'axios';
import { mapGetters } from 'vuex'

export default {
//   components: {/* 中略 */},
  data () {
    return {
      posts: [],
      messasgeContent: '',
      user: '' //仮　削除予定
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
    },
  //仮　削除予定
    async getUser () {
        const response = await axios.post('/api/login',{
            email: 'tanaka@gmail.com',
            password: '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        })

        //to-do 投稿エラーだった場合の処理

        this.user = response.data.user;

    }
  },
  watch: {
    $route: {
      async handler () {
        await this.fetchPosts()
      },
      immediate: true
    }
  },
  computed: {
    ...mapGetters('auth', [
        'isLoggedin'
    ])
  },
}
</script>
