/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['src/templates/**/*.phtml'],
  theme: {
    extend: {
      keyframes: {
        'fade-in': {
          "0%": {opacity: '0'},
          "100%": {opacity: '1'},
        },
        'fade-in-out': {
          "0%": {opacity: '0'},
          "10%": {opacity: '1'},
          "80%": {opacity: '1'},
          "100%": {opacity: '0', display: 'none'},
        }
      },
      animation: {
        'fade-in': 'fade-in ease-in 200ms',
        'fade-in-out': 'fade-in-out 2s ease-in-out forwards',
      }
    },
  },
  plugins: [],
}

