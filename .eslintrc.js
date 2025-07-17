module.exports = {
    env: {
        browser: true,
        es2021: true,
        node: true,
    },
    extends: [
        'eslint:recommended',
        '@vue/eslint-config-typescript',
        'plugin:vue/vue3-essential',
    ],
    parserOptions: {
        ecmaVersion: 'latest',
        sourceType: 'module',
    },
    plugins: ['vue'],
    rules: {
        'vue/multi-word-component-names': 'off',
        'vue/no-unused-vars': 'error',
        'no-unused-vars': 'warn',
        'no-console': 'warn',
    },
};
