/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    'templates/**/*.html.twig',
    'assets/scripts/*.js',
    //aprés avoir fait la commande npm install tw-elements
    './node_modules/tw-elements/dist/js/**/*.js'
  ],
  theme: {
    extend: {},
  },
  plugins: [
    //aprés avoir fait la commande npm install tw-elements
    require('tw-elements/dist/plugin')
  ],
}



