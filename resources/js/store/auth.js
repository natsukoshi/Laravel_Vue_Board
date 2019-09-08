// 認証用ストア
import axios from 'axios'

const state = {
    user: null
}

const getters = {
    username: state => state.user ? state.user.name : '',
    isLoggedin: state => !! state.user,
}

const mutations = {
    // ステートの値を更新する
    // ミューテーションの第一引数はstateで固定
    setUser (state, user) {
        state.user = user
    }
}

const actions = {
    // 会員登録APIを呼び出し登録。結果をuserステートに格納している
    async register (context, data) {
        const response = await axios.post('/api/register', data)
        console.log(response.data)

        context.commit('setUser', response.data)
      },
    // ログインAPIを呼び出し登録。結果をuserステートに格納している
    async login (context, data) {
        const response = await axios.post('/api/login', data)
        console.log(response.data)
        context.commit('setUser', response.data)
    },
    // ログアウトAPIを呼び出し。
    async logout (context) {
        const response = await axios.post('/api/logout')
        context.commit('setUser', null)
    },
    // ログイン済みのユーザを取得する。（ログイン状態を維持するため）
    async loggedinUser (context) {
        //　ログイン済みならユーザ情報を、ログインしていないなら空文字が返る
        const response = await axios.get('/api/user')
        //ログインしていなら、state:userの初期値のnullとする
        const userInfo = response.data !== '' ? response.data : null
        context.commit('setUser', userInfo)
    }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
