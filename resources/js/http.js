import axios from 'axios';

const http = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    }
});

http.interceptors.response.use(
    response => response,
    error => {
        if (!error.response) {
            alert('Unable to conntect to the server');
            return Promise.reject(errpr);
        }

        const status = error.response.status;

        switch (status) {
            case 401:
                window.location.href = '/login';
                break;
            case 419:
                window.location.reload();
                break;
            case 500:
                alert('A server error occurred.');
                break;
        }

        return Promise.reject(error);
    }
)

export default http;
