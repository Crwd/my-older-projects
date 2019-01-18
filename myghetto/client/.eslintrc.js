module.exports = {
  root: true,
  env: {
    node: true
  },
  'extends': [
    'plugin:vue/essential',
    '@vue/standard'
  ],
  rules: {
    'quotes': 0,
    'comma-dangle': 0,
    'no-tabs': 0
  },
  parserOptions: {
    parser: 'babel-eslint'
  }
}
