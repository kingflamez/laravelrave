const {description} = require('../../package')

module.exports = {
  /**
   * Ref：https://v1.vuepress.vuejs.org/config/#title
   */
  title: 'Laravel Flutterwave 🦄🦄',
  /**
   * Ref：https://v1.vuepress.vuejs.org/config/#description
   */
  description: 'A flutterwave laravel package to integrate with Laravel APIs seamlessly',

  /**
   * Extra tags to be injected to the page HTML `<head>`
   *
   * ref：https://v1.vuepress.vuejs.org/config/#head
   */
  head: [
    ['link', {rel: 'icon', href: `https://flutterwave.com/images/FLW-icon.png`}],
    ['meta', {name: 'theme-color', content: '#3eaf7c'}],
    ['meta', {name: 'apple-mobile-web-app-capable', content: 'yes'}],
    ['meta', {name: 'twitter:card', content: 'summary_large_image'}],
    ['meta', {name: 'twitter:url', content: 'https://laravelrave.netlify.app/'}],
    [
      'meta',
      {
        name: 'twitter:image:src',
        content: 'https://flutterwave.com/images/graph/home.png'
      }
    ],
    ['meta', {name: 'twitter:site', content: '@mrflamez_'}],
    ['meta', {name: 'twitter:creator', content: '@mrflamez_'}],
    ['meta', {name: 'og:type', content: 'website'}],
    ['meta', {name: 'og:url', content: 'https://laravelrave.netlify.app/'}],
    ['meta', {name: 'og:locale', content: 'en_US'}],
    [
      'meta',
      {
        name: 'og:image',
        content: 'https://flutterwave.com/images/graph/home.png'
      }
    ],
    ['meta', {name: 'og:locale', content: 'en_US'}],
    [
      'meta',
      {
        name: 'keywords',
        content:
          'online payments Nigeria, pay online in Nigeria, payment platforms in Nigeria, payment gateways in Nigeria, top 10 online payment processing platforms, top online payment gateways in Nigeria, best online payment gateways in Nigeria, online payment gateways in nigeria for e-commerce, payment platforms in Nigeria, Nigeria online payment platforms, laravel payment package, flutterwave laravel'
      }
    ],
    [
      'meta',
      {
        name: 'description',
        content: 'A flutterwave laravel package to integrate with Laravel APIs seamlessly'
      }
    ],
    [
      'meta',
      {
        name: 'twitter:title',
        content: 'Laravel Flutterwave 🦄🦄 | Integrate Flutterwave APIs seamlessly'
      }
    ],
    [
      'meta',
      {
        name: 'twitter:description',
        content: 'A flutterwave laravel package to integrate with Laravel APIs seamlessly'
      }
    ],
    [
      'meta',
      {
        name: 'og:site_name',
        content: 'Laravel Flutterwave 🦄🦄 | Integrate Flutterwave APIs seamlessly'
      }
    ],
    [
      'meta',
      {
        name: 'og:title',
        content: 'Laravel Flutterwave 🦄🦄 | Integrate Flutterwave APIs seamlessly'
      }
    ],
    [
      'meta',
      {
        name: 'og:description',
        content: 'A flutterwave laravel package to integrate with Laravel APIs seamlessly'
      }
    ],
    ['meta', {name: 'apple-mobile-web-app-status-bar-style', content: 'black'}]
  ],
  nav: [
    {text: 'Home', link: '/'},
    {
      text: 'Installation',
      link: '/getting-started/installation.html'
    },
    {
      text: 'Payment Implementation',
      link: '/getting-started/payment-implementation.html'
    }
  ],

  /**
   * Theme configuration, here is the default theme configuration for VuePress.
   *
   * ref：https://v1.vuepress.vuejs.org/theme/default-theme-config.html
   */
  themeConfig: {
    // Assumes GitHub. Can also be a full GitLab url.
    repo: 'kingflamez/laravelrave',
    docsRepo: 'kingflamez/laravelrave',
    docsDir: 'docs',
    // defaults to false, set to true to enable
    editLinks: true,
    // custom text for edit link. Defaults to "Edit this page"
    editLinkText: 'Help us improve this page!',
    nav: [
      {text: 'Home', link: '/'},
      {
        text: 'Installation',
        link: '/getting-started/installation.html'
      },
      {
        text: 'Payment Implementation',
        link: '/getting-started/payment-implementation.html'
      }
    ],
    lastUpdated: true,
    sidebar: [
      {
        title: 'Getting Started',
        children: ['/getting-started/installation.html', '/getting-started/payment-implementation.html']
      },
      {
        title: ' Verification/Notification',
        children: ['/verification/introduction.html', '/verification/webhook.html', '/verification/callback.html']
      },
      {
        title: 'Payments using APIs',
        children: [
          '/payments/introduction.html',
          '/payments/ach-payments.html',
          '/payments/ngn-bank-transfer.html',
          '/payments/gh-mobile-money.html',
          '/payments/rw-mobile-money.html',
          '/payments/ug-mobile-money.html',
          '/payments/zambia-mobile-money.html',
          '/payments/mpesa-mobile-money.html',
          '/payments/voucher-payments.html',
          '/payments/francophone-mobile-money.html'
        ]
      },
      {
        title: 'Transfers',
        children: [
          '/transfers/introduction.html',
          '/transfers/initiate-transfers.html',
          '/transfers/retry-transfer.html',
          '/transfers/bulk-transfer.html',
          '/transfers/fees.html',
          '/transfers/fetch-transfers.html',
          '/transfers/fetch-a-transfer.html',
          '/transfers/retry-transfer-status.html',
          '/transfers/transfer-rates.html'
        ]
      },
      {
        title: 'Beneficiaries',
        children: ['/beneficiaries/create-beneficiary.html', '/beneficiaries/list-beneficiaries.html', '/beneficiaries/fetch-beneficiary.html', '/beneficiaries/delete-beneficiary.html']
      },
      {
        title: 'Banks',
        children: ['/banks/list-banks.html', '/banks/bank-branches.html']
      }
    ],
    displayAllHeaders: true
  },
  markdown: {
    lineNumbers: true
  },

  /**
   * Apply plugins，ref：https://v1.vuepress.vuejs.org/zh/plugin/
   */
  plugins: ['@vuepress/plugin-back-to-top', '@vuepress/plugin-medium-zoom']
}
