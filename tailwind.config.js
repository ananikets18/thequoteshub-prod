/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.php",
    "./app/views/**/*.php",
    "./app/views/**/*.html",
    "./public/**/*.html",
    "./public/assets/js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        // Add your custom colors here if needed
      },
      fontFamily: {
        // Add custom fonts here if needed
      },
    },
  },
  plugins: [],
  // Production optimizations
  future: {
    hoverOnlyWhenSupported: true,
  },
}
