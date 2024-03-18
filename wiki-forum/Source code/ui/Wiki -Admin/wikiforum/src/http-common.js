import axios from "axios";

export default axios.create({
    baseURL: "http://localhost:8000/api",
    //    baseURL: "https://wikiforum-api.innovaturelabs.com/api",


    headers: {
        // "Content-type": "application/json",
        'Cache-Control': 'no-cache',
        Authorization: `Bearer ` + JSON.parse(localStorage.getItem("TOKEN")),
    }
});
