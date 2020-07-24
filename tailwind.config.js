module.exports = {
  purge: {
    enabled: true,
    content: ["./**/*.php"],
    options: {
      whitelist: ["bg-blue-500"],
    },
  },
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [],
};
