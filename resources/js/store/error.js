const state = {
    statusCode: null
  }

  const mutations = {
      //API実行時のレスポンスコードを格納する
    setStatusCode (state, statusCode) {
      state.statusCode = statusCode
    }
  }

  export default {
    namespaced: true,
    state,
    mutations
  }
