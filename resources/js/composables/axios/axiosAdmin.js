import { router } from "@/plugins/1.router";
import axios from "axios";
var axiosAdmin = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL + "/api" || "/api",
});

// Set default headers
axiosAdmin.defaults.headers["Accept"] = "application/json";
axiosAdmin.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Set JWT token if available
const token = localStorage.getItem("gms_token");
if (token) {
  axiosAdmin.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

// Axios error interceptor
axiosAdmin.interceptors.response.use(
  function (response) {
    return response.data;
  },
  function (error) {
    if (!error.response) {
      console.error("Network error:", error.message);
      return Promise.reject(error);
    }

    const errorCode = error.response.status;

    if (
      errorCode === 401 
    ) {
      delete axiosAdmin.defaults.headers.common["Authorization"];
      // If error 401 redirect to login
      delete axiosAdmin.defaults.headers.common.Authorization;
      window.location.href = import.meta.env.VITE_API_BASE_URL + "/login";

      localStorage.removeItem("gms_token");
      localStorage.removeItem("gms_user");
    } else if (errorCode === 400) {
      console.error("Bad Request:", error.response.data.error);
    } else if (errorCode === 403) {
      router.push({ name: "forbidden" });
      console.error("Forbidden:", error.response.data.error);
    } else if (errorCode === 404) {
      console.error("Not Found:", error.response.data.error);
    } else if (errorCode === 405) {
      console.warn("Method Not Allowed:", error.response.data.error);
    }

    return Promise.reject(error.response);
  }
);

export default axiosAdmin;
