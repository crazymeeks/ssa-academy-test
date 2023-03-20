import axios from "axios";

import {setupInterceptorsTo} from './interceptor';

const api = setupInterceptorsTo(
    axios.create({
        baseURL: import.meta.env.VITE_ADMIN_URL,
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
    })
);


export default api;