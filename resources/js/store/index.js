//　各用途のストアをまとめて記述

import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'   //認証用ストア
import error from './error' //エラー用ストア

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error,
  }
})

export default store
