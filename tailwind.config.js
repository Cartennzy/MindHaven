/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: "#01588E",
                accent: "#41AD01",
            },
            boxShadow: {
                soft: "0 20px 45px rgba(1, 88, 142, 0.12)",
            },
        },
    },
    plugins: [],
};