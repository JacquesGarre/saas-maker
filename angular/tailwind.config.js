/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,ts}",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
  safelist: [
    {
      pattern: /bottom-(.)/,
    },
    {
      pattern: /right-(.)/,
    },
    {
      pattern: /top-(.)/,
    },
    {
      pattern: /left-(.)/,
    },
    {
      pattern: /bg-(.)/,
    },
    {
      pattern: /mx-(.)/,
    },
    {
      pattern: /px-(.)/,
    },
    'max-w-xs',
    'w-full'
  ]
}

