module.exports = {
  purge: {
    enabled: true,
    content: ["./**/*.php"],
    options: {
      whitelist: ["bg-blue-500"],
    },
  },
  theme: {
    screens: {
      "xs": { "max": "640px" },
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
    },
    extend: {},
  },
  variants: {},
  plugins: [],
};
