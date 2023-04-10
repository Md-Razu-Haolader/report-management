/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/**/*.blade.php", "./resources/**/*.tsx"],
    theme: {
        extend: {},
    },
    plugins: [require("@tailwindcss/forms")],
};
