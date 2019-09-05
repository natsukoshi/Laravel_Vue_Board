// 認証用ストア
import axios from 'axios'

const state = {
    user: null
}

const getters = {}

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
        context.commit('setUser', response.data)
      }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
