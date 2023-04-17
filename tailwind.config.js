/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./views/**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        'primary' : {
          50  : 'hsl( 197deg 33% 95% / <alpha-value> )',
          100 : 'hsl( 197deg 33% 90% / <alpha-value> )',
          200 : 'hsl( 197deg 33% 80% / <alpha-value> )',
          300 : 'hsl( 197deg 33% 70% / <alpha-value> )',
          400 : 'hsl( 197deg 33% 60% / <alpha-value> )',
          500 : 'hsl( 197deg 33% 50% / <alpha-value> )',
          600 : 'hsl( 197deg 33% 40% / <alpha-value> )',
          700 : 'hsl( 197deg 33% 30% / <alpha-value> )',
          800 : 'hsl( 197deg 33% 20% / <alpha-value> )',
          900 : 'hsl( 197deg 33% 10% / <alpha-value> )'
        },
        'secondary' : {
          50  : 'hsl( 22deg 80% 95% / <alpha-value> )',
          100 : 'hsl( 22deg 80% 90% / <alpha-value> )',
          200 : 'hsl( 22deg 80% 80% / <alpha-value> )',
          300 : 'hsl( 22deg 80% 70% / <alpha-value> )',
          400 : 'hsl( 22deg 80% 60% / <alpha-value> )',
          500 : 'hsl( 22deg 80% 50% / <alpha-value> )',
          600 : 'hsl( 22deg 80% 40% / <alpha-value> )',
          700 : 'hsl( 22deg 80% 30% / <alpha-value> )',
          800 : 'hsl( 22deg 80% 20% / <alpha-value> )',
          900 : 'hsl( 22deg 80% 10% / <alpha-value> )'
        }
      },
    },
  },
  plugins: [],
  important : true
}

