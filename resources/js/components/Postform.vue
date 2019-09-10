<template>
    <div>
        <form @submit.prevent="postMessage" v-if="isLoggedin">
            タイトル：<input type="text" v-model="titleContent"><br>
            メッセージ：<textarea v-model="messasgeContent"></textarea><br>
            <button>メッセージ送信</button>
        </form>
    </div>
</template>
<script>
import axios from 'axios';
import { mapGetters } from 'vuex'

export default {
  data () {
    return {
      titleContent:'',
      messasgeContent: '',
    }
  },
  methods: {
    async postMessage () {
        const response = await axios.post('/api/posts',{
            title: this.titleContent,
            message: this.messasgeContent,
        })

        //to-do 投稿エラーだった場合の処理
        // エラーレスポンスを受け取ったときエラーページへ飛ばす

        this.titleContent = '';
        this.messasgeContent = '';

        this.$emit('reloadPosts');
    },
  },
  computed: {
    ...mapGetters('auth', [
        'isLoggedin'
    ])
  },
}
</script>
