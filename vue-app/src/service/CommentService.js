import Api from "./Api";

const CommentService = {
    store(data) {
        return Api().post('/comment/store', data);
    },
    delete(id) {
        return Api().delete('/comment/' + id);
    },
};

export default CommentService;
