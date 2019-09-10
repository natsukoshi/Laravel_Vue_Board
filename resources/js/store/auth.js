// 認証用ストア

import axios from 'axios'
import { OK, UNPROCESSABLE_ENTITY } from '../util'

const state = {
    user: null,
    apiStatus: null,
    loginErrorMessages: null,
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
    },
    setApiStatus (state, status) {
        state.apiStatus = status
    },
    setloginErrorMessages (state, messsages) {
        state.loginErrorMessages = messsages
    },
}

const actions = {
    // 会員登録APIを呼び出し登録。結果をuserステートに格納している
    async register (context, data) {
        context.commit('setApiStatus', null)

        const response = await axios.post('/api/register', data)
                                    .catch( function(err) { return ( err.response || err) } )
        console.log(response.data)
        if(response.status === OK){
            context.commit('setApiStatus', true)
            context.commit('setUser', response.data)
            return false
        }

        context.commit('setApiStatus', false)

        // あるストアモジュールから別のモジュール(グローバル名前空間)のミューテーションを
        // commit する場合は第三引数に { root: true } を追加します。
        context.commit('error/setStatusCode', response.status, { root: true })

      },

    // ログインAPIを呼び出し。レスポンスをuserステートに格納している
    async login (context, data) {
      console.log('ログイン　アクション実行中')

        context.commit('setApiStatus', null)
        const response = await axios.post('/api/login', data)
                                            .catch(err => err.response || err)
        //.catch( function(err) { return ( err.response || err) } )

      console.log('ログイン　API　POST後')


        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', response.data)
            return false
          }

          context.commit('setApiStatus', false)
        if(response.status === UNPROCESSABLE_ENTITY){
             console.log('エラーの場合の処理:' + response.status)

            context.commit('setloginErrorMessages', response.data.errors)
            console.log(response.data.errors)
        }else{
            context.commit('error/setStatusCode', response.status, { root: true })
        }

    },

    // ログアウトAPIを呼び出し
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
