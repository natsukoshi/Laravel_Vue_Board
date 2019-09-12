<template>
    <div class="post_form">
        <div v-if="postErrors" class="error">
            <ul v-if="postErrors.title">
                <li v-for="msg in postErrors.title" :key="msg">{{ msg }}</li>
            </ul>
             <ul v-if="postErrors.message">
                <li v-for="msg in postErrors.message" :key="msg">{{ msg }}</li>
            </ul>
        </div>
        <form @submit.prevent="postMessage" v-if="isLoggedin">
            タイトル：<input type="text" v-model="titleContent"><br>
            メッセージ：<textarea v-model="messasgeContent"></textarea><br>
            <button>メッセージ送信</button>
        </form>
    </div>
</template>
<script>
import axios from 'axios';
import { mapGetters, mapState } from 'vuex'
import { OK, UNPROCESSABLE_ENTITY } from '../util'

export default {
  data () {
    return {
      titleContent:'',
      messasgeContent: '',
      postErrors: '',
    }
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
    }),
    ...mapGetters('auth', [
        'isLoggedin'
    ])
  },
  methods: {
    async postMessage () {
        const response = await axios.post('/api/posts',{
            title: this.titleContent,
            message: this.messasgeContent,
        }).catch(err => err.response || err)


        //バリデーションエラー
        if(response.status === UNPROCESSABLE_ENTITY){
            this.postErrors = response.data.errors
            return
        }

        //to-do 投稿エラーだった場合の処理
        // エラーレスポンスを受け取ったときエラーページへ飛ばす

        this.titleContent = '';
        this.messasgeContent = '';

        this.$emit('reloadPosts');
    },
  },
}
</script>
