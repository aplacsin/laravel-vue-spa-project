import Api from "./Api";

const VideoService = {
    video(page) {
        return Api().get('/videos?page=' + page);
    },

    show(id) {
        return Api().get('/videos/show/' + id);
    },    
};

export default VideoService;