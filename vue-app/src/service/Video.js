import Api from "./Api";

export default {
    video(page) {
        return Api().get('/videos?page=' + page);
    },

    show(id) {
        return Api().get('/videos/show/' + id);
    },    
};