/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/**/*.blade.php",
        "./resources/views/**/*.blade.php",
        "./storage/framework/views/*.php",
        './resources/views/vendor/pagination/*.blade.php'
        // "./resources/**/*.js",
        // "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // Mapping ke CSS Variables dari app.css Anda
                background: 'var(--background)',
                foreground: 'var(--foreground)',
                card: 'var(--card)',
                'card-foreground': 'var(--card-foreground)',
                popover: 'var(--popover)',
                'popover-foreground': 'var(--popover-foreground)',
                primary: 'var(--primary)', // --> #0F6643
                'primary-foreground': 'var(--primary-foreground)', // --> #ffffff
                secondary: 'var(--secondary)',
                'secondary-foreground': 'var(--secondary-foreground)',
                muted: 'var(--muted)',
                'muted-foreground': 'var(--muted-foreground)',
                accent: 'var(--accent)', // --> #60A5FA (biru untuk item aktif)
                'accent-foreground': 'var(--accent-foreground)', // --> #ffffff
                destructive: 'var(--destructive)',
                'destructive-foreground': 'var(--destructive-foreground)',
                border: 'var(--border)',
                input: 'var(--input)',
                ring: 'var(--ring)',

                // Warna khusus sidebar
                'sidebar-border': 'var(--sidebar-border)', // --> rgba(255, 255, 255, 0.1)
                'sidebar-accent': 'var(--sidebar-accent)',
                'sidebar-accent-foreground': 'var(--sidebar-accent-foreground)',
            },
            borderRadius: {
                lg: 'var(--radius)',
                md: 'calc(var(--radius) - 2px)',
                sm: 'calc(var(--radius) - 4px)',
            },
        },
    },
    plugins: [],
};
