import Api from './Api'

const PostService = {
    list(params) {
        return Api().get('/posts' + params)
    },

    show(id) {
        return Api().get('/posts/show/' + id)
    },

    edit(id) {
        return Api().get('/posts/edit/' + id)
    },

    update(id, data) {
        return Api().put('/posts/update/' + id, data)
    },

    delete(id) {
        return Api().delete('/posts/destroy/' + id)
    },

    export(params) {
        return Api().get('/posts/export' + params, {responseType: 'blob'})
    },

    import(data) {
        return Api().post('/posts/import', data, { headers: { 'content-type': 'multipart/form-data' } })
    }
}

export default PostService
