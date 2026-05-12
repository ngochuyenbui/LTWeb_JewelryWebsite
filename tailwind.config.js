/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/views/**/*.php",
    "./public/**/*.php",
    "./public/assets/js/**/*.js",
    "./app/views/**/components/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Montserrat", "sans-serif"],
        serif: ['"Playfair Display"', "serif"],
      },
    },
  },
  plugins: [],
};
