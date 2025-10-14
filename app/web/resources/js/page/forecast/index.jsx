import React from "react";
import ReactDOM from "react-dom/client";
import Forecast from "@components/Forecast";

document.addEventListener("DOMContentLoaded", () => {
    try {
        const el = document.getElementById("forecast");

        if (el) {
            ReactDOM.createRoot(el).render(<Forecast />);
        }
    } catch (error) {
        console.error("React render error:", error);
    }
});
