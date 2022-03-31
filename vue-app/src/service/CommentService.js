import Api from "./Api";

const CommentService = {
    store(data) {
        return Api().post('/comment/store', data);
    },

    delete(id) {
        return Api().delete('/comment/' + id);
    },

    edit(id) {
        return Api().get('/comment/edit/' + id);
    },

    update(id, data) {
        return Api().put('/comment/update/' + id, data);
    }
};

export default CommentService;
