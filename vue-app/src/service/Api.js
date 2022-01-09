import axios from "axios";
import router from "@/router";

const BaseApi = axios.create({
    baseURL: "http://localhost/api",
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json"
    }
});

const Api = function () {
    const token = localStorage.getItem("token");

    if (token) {
        BaseApi.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    }

    return BaseApi;
};

BaseApi.interceptors.response.use(null, error => {
    let path;
    switch (error.response.status) {
        case 401:
            path = '/login';
            break;
        case 403:
            path = '/403';
            break;
    }
    router.push(path).then(() => {
        // Do something after login is successful.
    });
    return Promise.reject(error);
});

export default Api;