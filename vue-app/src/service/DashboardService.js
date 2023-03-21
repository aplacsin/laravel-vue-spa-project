import Api from './Api';

const DashboardService = {
    list() {
        return Api().get('/stat');
    },
}

export default DashboardService;
