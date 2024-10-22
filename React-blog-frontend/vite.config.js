import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      "/api": {
        target: "http://react_blog.test",
        changeOrigin: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      },
    },
  },
});
