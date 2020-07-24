module.exports = {
  purge: {
    enabled: true,
    content: ["./**/*.php"],
    options: {
      whitelist: ["bg-blue-500", "mode-dark"],
    },
  },
  theme: {
    screens: {
      xs: { max: "640px" },
      sm: "640px",
      md: "768px",
      lg: "1024px",
      xl: "1280px",
      dark: { raw: "(prefers-color-scheme: dark)" },
    },
    extend: {},
  },
  variants: {},
  plugins: [require("postcss-import"), require("tailwindcss-dark-mode")()],
};
