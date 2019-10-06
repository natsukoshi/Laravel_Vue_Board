export const OK = 200
export const CREATED = 201
export const INTERNAL_SERVER_ERROR = 500
export const UNPROCESSABLE_ENTITY = 422 //Laravelではバリデーションエラー時に422を返す
export const NOT_FOUND = 404 //404エラー
export const CSRF_TOKEN_ERROR = 419 //CSRFTOKENエラー

//フォーム用定数　どのフォームか判定用
export const POST_PAGE = 1
export const REPLY_PAGE = 2


/**
 * クッキーの値を取得する
 * @param {String} searchKey 検索するキー
 * @returns {String} キーに対応する値
 */
export function getCookieValue(searchKey) {
    if (typeof searchKey === 'undefined') {
        return ''
    }

    let val = ''

    document.cookie.split(';').forEach(cookie => {
        const [key, value] = cookie.split('=')
        if (key === searchKey) {
            return val = value
        }
    })

    return val
}
