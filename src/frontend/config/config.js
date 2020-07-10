import baseConfig from './config.base';

const config = {
  ...baseConfig,
  environment: 'docker',
  api_url: 'https://api.marketplace.docker',
  base_host: 'https://marketplace.docker',
};

export default config;
