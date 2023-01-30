// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose.

// Set Preflight flag and Tailwind Typography class name based on the build
// target.
let includePreflight, typographyClassName;
if ("editor" === process.env._TW_TARGET) {
  includePreflight = false;
  typographyClassName = "block-editor-block-list__layout";
} else {
  includePreflight = true;
  typographyClassName = "prose";
}

module.exports = {
  safelist: [
    "gap-5",
    "gap-20",
    "flex",
    "flex-col",
    "flex-row",
    "md:gap-20",
    "flex",
    "md:flex-col",
    "md:flex-row",
    "md:gap-10",
    "lg:gap-20",
    "lg:px-10",
    "hidden",
    "lg:block",
    "md:items-end",
    "order-1",
    "order-2",
    "md:order-1",
    "md:order-2",
    "my-20",
  ],
  presets: [
    // Manage Tailwind Typography's configuration in a separate file.
    require("./tailwind-typography.config.js"),
  ],
  content: [
    // Ensure changes to PHP files and `theme.json` trigger a rebuild.
    "./theme/**/*.php",
    "./theme/theme.json",
  ],
  theme: {
    container: {
      center: true,
      screens: {
        sm: "100%",
        md: "100%",
        lg: "1024px",
        xl: "1160px",
      },
    },
    extend: {
      colors: {
        purple: {
          50: "#CDC3F4",
          100: "#BEB2F0",
          200: "#A290EA",
          300: "#856DE3",
          400: "#684BDD",
          500: "#4726CA",
          600: "#361D9B",
          700: "#26146B",
          800: "#150B3C",
          900: "#05020D",
        },
        lila: {
          DEFAULT: "#7488ED",
          50: "#CED5F9",
          100: "#BCC6F6",
          200: "#98A7F2",
          300: "#7488ED",
          400: "#425DE7",
          500: "#1C3AD5",
          600: "#152DA4",
          700: "#0F1F72",
          800: "#081240",
          900: "#02040F",
        },
        seagull: {
          DEFAULT: "#7EC7E5",
          50: "#D3ECF6",
          100: "#C2E4F3",
          200: "#A0D6EC",
          300: "#7EC7E5",
          400: "#4FB3DC",
          500: "#299BCA",
          600: "#1F779B",
          700: "#16536D",
          800: "#0C303E",
          900: "#030C0F",
        },
        mabel: {
          DEFAULT: "#DAF8FF",
          50: "#FFFFFF",
          100: "#FFFFFF",
          200: "#DAF8FF",
          300: "#A2EDFF",
          400: "#6AE3FF",
          500: "#32D8FF",
          600: "#00CAF9",
          700: "#009CC1",
          800: "#006F88",
          900: "#004150",
        },
        "royal-blue": {
          DEFAULT: "#2B56DF",
          50: "#CBD5F7",
          100: "#B9C7F4",
          200: "#95ABEF",
          300: "#728FEA",
          400: "#4E72E4",
          500: "#2B56DF",
          600: "#1C41B6",
          700: "#142F86",
          800: "#0D1E55",
          900: "#050D24",
        },
      },
    },
    fontFamily: {
      display: ["Anybody", "sans-serif"],
      body: ["Poppins", "sans-serif"],
    },
  },
  corePlugins: {
    // Disable Preflight base styles in CSS targeting the editor.
    preflight: includePreflight,
  },
  plugins: [
    // Extract colors and widths from `theme.json`.
    require("@_tw/themejson")(require("../theme/theme.json")),

    // Add Tailwind Typography.
    require("@tailwindcss/typography")({
      className: typographyClassName,
    }),

    // Uncomment below to add additional first-party Tailwind plugins.
    // require( '@tailwindcss/forms' ),
    // require( '@tailwindcss/aspect-ratio' ),
    // require( '@tailwindcss/line-clamp' ),
    // require( '@tailwindcss/container-queries' ),
  ],
};
