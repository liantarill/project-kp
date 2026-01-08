import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: "#004B23",
                    light: "#60a5fa",
                    // dark: "#1d4ed8",
                },
                secondary: {
                    DEFAULT: "#64748b", // slate-500
                },
                success: "#22c55e",
                warning: "#facc15",
                danger: "#ef4444",
            },
        },
    },

    plugins: [forms],
};
