/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./app/**/*.{html,js,php}"],
  theme: {
    extend: {
      container: {
        center: true,
      },
      colors: {
        "blue-base": "#1a73e8",
        "dark-base": "#111827",
        "light-base": "#f9fafb",
        "purple-base": "#7c3aed",
        "red-base": "#dc2626",
      },
      fontFamily: {
        poppins: "Poppins",
      },
    },
  },
  plugins: [],
};
