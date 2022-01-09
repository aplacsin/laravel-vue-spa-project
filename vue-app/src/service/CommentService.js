import Api from "./Api";

const CommentService = {
    store(data) {
        return Api().post('/comment/store', data);
    },
};

export default CommentService;