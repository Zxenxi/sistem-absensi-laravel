import defaultTheme from "tailwindcss/defaultTheme";
import plugin from "tailwindcss/plugin";
import forms from "@tailwindcss/forms";
import Color from "color";

export default {
    dark: "class", // Ubah dari "media" ke "class"
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            maxHeight: {
                0: "0",
                xl: "36rem",
            },
        },
        colors: {
            transparent: "transparent",
            white: "#ffffff",
            black: "#000000",
            gray: {
                50: "#f9fafb",
                100: "#f4f5f7",
                200: "#e5e7eb",
                300: "#d5d6d7",
                400: "#9e9e9e",
                500: "#707275",
                600: "#4c4f52",
                700: "#24262d",
                800: "#1a1c23",
                900: "#121317",
            },
            "cool-gray": {
                50: "#fbfdfe",
                100: "#f1f5f9",
                200: "#e2e8f0",
                300: "#cfd8e3",
                400: "#97a6ba",
                500: "#64748b",
                600: "#475569",
                700: "#364152",
                800: "#27303f",
                900: "#1a202e",
            },
            red: {
                50: "#fdf2f2",
                100: "#fde8e8",
                200: "#fbd5d5",
                300: "#f8b4b4",
                400: "#f98080",
                500: "#f05252",
                600: "#e02424",
                700: "#c81e1e",
                800: "#9b1c1c",
                900: "#771d1d",
            },
            blue: {
                50: "#ebf5ff",
                100: "#e1effe",
                200: "#c3ddfd",
                300: "#a4cafe",
                400: "#76a9fa",
                500: "#3f83f8",
                600: "#1c64f2",
                700: "#1a56db",
                800: "#1e429f",
                900: "#233876",
            },
        },
    },

    variants: {
        backgroundColor: [
            "hover",
            "focus",
            "active",
            "odd",
            "dark",
            "dark:hover",
            "dark:focus",
            "dark:active",
            "dark:odd",
        ],
        display: ["responsive", "dark"],
        textColor: [
            "focus-within",
            "hover",
            "active",
            "dark",
            "dark:focus-within",
            "dark:hover",
            "dark:active",
        ],
        placeholderColor: ["focus", "dark", "dark:focus"],
        borderColor: ["focus", "hover", "dark", "dark:focus", "dark:hover"],
        divideColor: ["dark"],
        boxShadow: ["focus", "dark:focus"],
    },

    plugins: [
        // require("tailwindcss-multi-theme"),
        forms,
        plugin(({ addUtilities, theme, variants }) => {
            const newUtilities = {};
            Object.entries(theme("colors")).forEach(([name, value]) => {
                if (name === "transparent" || name === "current") return;
                const color = value[300] ? value[300] : value;
                const hsla = Color(color).alpha(0.45).hsl().string();

                newUtilities[`.shadow-outline-${name}`] = {
                    "box-shadow": `0 0 0 3px ${hsla}`,
                };
            });

            addUtilities(newUtilities, variants("boxShadow"));
        }),
    ],
};
