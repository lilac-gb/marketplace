module.exports = {
  apps: [
    {
      name: 'mp.nuxt',
      exec_mode: 'cluster',
      instances: 'max', // Or a number of instances
      script: './src/frontend/node_modules/nuxt/bin/nuxt.js',
      args: 'start',
      autorestart: true,
      watch: false,
      max_memory_restart: '1G',
      instance_var: 'PORT',
      env: {
        "PORT": 3000,
        "SERVER_HOST": "127.0.0.1",
        "NODE_APP_INSTANCE": "development"
      },
    },
  ],
};
