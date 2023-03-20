// Blog for this code can be found at https://webera.blog/implement-refresh-token-with-jwt-in-react-app-using-axios-1910087c3d7

const onRequest = (config) => {
    config.headers ={
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    return config;
};

const onRequestError = (error) => {
    return Promise.reject(error);
};

const onResponse = (response) => {
    return response;
};


const onResponseError = (error) => {
    
    return Promise.reject(error);
};

export const setupInterceptorsTo = (axiosInstance) => {
    axiosInstance.interceptors.request.use(onRequest, onRequestError);
    axiosInstance.interceptors.response.use(onResponse, onResponseError);

    return axiosInstance;
};