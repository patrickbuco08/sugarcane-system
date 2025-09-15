import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Figtree", ...defaultTheme.fontFamily.sans],
      },
      colors: {
        honey: {
          DEFAULT: "#DAA520", // honey-gold
          light: "#FFBF00", // amber
          dark: "#A97100", // dark honey
        },
        theme: {
          primary: "#3F4B44",
          secondary: "#8B7355",  // Warm brown that complements the green and gold
          accent: "#DAA520",
          text: "#E5E5E5",
        },
        sugarcane: {
          DEFAULT: "#2E3A32", // 🌿 Main dark background
          soft: "#3F4B44", // 🧱 For cards or secondary containers
          surface: "#232B26", // 🖥️ Deep background (main layout wrapper)
          border: "#5A6B5F", // ✏️ For borders, dividers, input outlines
          text: "#E5E5E5", // 📄 Main text (light on dark)
          textMuted: "#A9B3AA", // 📎 Muted text for hints, placeholders
          success: "#4CAF50", // ✅ Green tone for success messages
          danger: "#E74C3C", // ❌ For errors, danger buttons
          warning: "#F39C12", // ⚠️ For warning or caution areas
          info: "#3498DB", // ℹ️ For informational badges or alerts
        },
        sugarcaneLight: {
          DEFAULT: "#EAEFED", // 🌿 Main light background
          soft: "#D6DBD8", // 🧱 For cards or secondary containers (light)
          surface: "#F5F7F6", // 🖥️ Top-level background
          border: "#BCC5C0", // ✏️ Border color in light mode
          text: "#2E3A32", // 📄 Main text on light backgrounds
          textMuted: "#5A6B5F", // 📎 Muted text for descriptions, placeholders
          success: "#4CAF50", // ✅ Success (same as dark)
          danger: "#E74C3C", // ❌ Danger (same as dark)
          warning: "#F39C12", // ⚠️ Warning (same as dark)
          info: "#3498DB", // ℹ️ Info (same as dark)
        },
      },
    },
  },
  plugins: [],
};
