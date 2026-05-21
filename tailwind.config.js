/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                dark: '#1B262C',       // Deep Navy
                secondary: '#0F4C75',  // Royal Ink
                primary: '#3282B8',    // Electric Blue
                light: '#BBE1FA',      // Frost White
                base: '#FFFFFF',       // Pure Base
                danger: '#e11d48',
                success: '#059669',
                warning: '#d97706'
            },
            fontFamily: {
                heading: ['Montserrat', 'sans-serif'],
                sans: ['"Open Sans"', 'sans-serif'],
            }
        }
    },
    plugins: [],
}
