import Api from "./Api";

const VideoService = {
    video(params) {
        return Api().get('/videos' + params);
    },

    show(id) {
        return Api().get('/videos/show/' + id);
    },
};

export default VideoService;