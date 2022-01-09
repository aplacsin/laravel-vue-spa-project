import Api from "./Api";

const PostService = {
    list(page) {
        return Api().get('/posts' + page);
    },

    show(id) {
        return Api().get('/posts/show/' + id);
    },

    edit(id) {
        return Api().get('/posts/edit/' + id);
    },

    update(id, data) {
        return Api().put('/posts/update/' + id, data);
    },

    delete(id) {
        return Api().delete('/posts/destroy/' + id);
    },
};

export default PostService;