/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        testbg: '#3333cc', // similar to #3c (dark blueish)
      },
    },
  },
  plugins: [],
}
