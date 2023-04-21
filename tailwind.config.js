/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./views/**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        'primary' : {
          50  : 'hsl( var(--color-primary) 95% / <alpha-value> )',
          100 : 'hsl( var(--color-primary) 90% / <alpha-value> )',
          150 : 'hsl( var(--color-primary) 86% / <alpha-value> )',
          200 : 'hsl( var(--color-primary) 80% / <alpha-value> )',
          300 : 'hsl( var(--color-primary) 70% / <alpha-value> )',
          400 : 'hsl( var(--color-primary) 60% / <alpha-value> )',
          500 : 'hsl( var(--color-primary) 50% / <alpha-value> )',
          600 : 'hsl( var(--color-primary) 40% / <alpha-value> )',
          700 : 'hsl( var(--color-primary) 30% / <alpha-value> )',
          800 : 'hsl( var(--color-primary) 20% / <alpha-value> )',
          900 : 'hsl( var(--color-primary) 10% / <alpha-value> )'
        },
        'secondary' : {
          50  : 'hsl( var(--color-secondary) 95% / <alpha-value> )',
          100 : 'hsl( var(--color-secondary) 90% / <alpha-value> )',
          200 : 'hsl( var(--color-secondary) 80% / <alpha-value> )',
          300 : 'hsl( var(--color-secondary) 70% / <alpha-value> )',
          400 : 'hsl( var(--color-secondary) 60% / <alpha-value> )',
          500 : 'hsl( var(--color-secondary) 50% / <alpha-value> )',
          600 : 'hsl( var(--color-secondary) 40% / <alpha-value> )',
          700 : 'hsl( var(--color-secondary) 30% / <alpha-value> )',
          800 : 'hsl( var(--color-secondary) 20% / <alpha-value> )',
          900 : 'hsl( var(--color-secondary) 10% / <alpha-value> )'
        }
      },
    },
  },
  plugins: [],
  important : true
}

