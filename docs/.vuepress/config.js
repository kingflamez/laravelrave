module.exports = {
    title: 'Laravel Rave',
    description: 'A rave by flutterwave laravel package to accept payment globally in multi currencies',
    themeConfig: {
        // Assumes GitHub. Can also be a full GitLab url.
        repo: 'kingflamez/laravelrave',
        docsRepo: 'kingflamez/kingflamez.github.io',
        docsDir:'laravelrave',
        // defaults to false, set to true to enable
        editLinks: true,
        // custom text for edit link. Defaults to "Edit this page"
        editLinkText: 'Help us improve this page!',
        nav: [
          { text: 'Home', link: '/' },
          { text: 'Installation', link: '/getting-started/installation.html' },
          { text: 'Payment Implementation', link: '/getting-started/payment-implementation.html' },
        ],
        head: [
            ['link', { rel: 'icon', href: `https://rave.flutterwave.com/favicon.ico` }],
            ['link', { rel: 'manifest', href: '/manifest.json' }],
            ['meta', { name: 'msapplication-TileColor', content: '#000000' }]
        ],
        sidebar: [
            {
                title: 'Implementations',
                children: [
                    '/getting-started/installation.html',
                    '/getting-started/payment-implementation.html',
                    '/recurring/introduction.html',
                    '/webhooks.html',
                    '/verify-payment.html',
                ]
            },
            {
                title: 'Miscs',
                children: [ 
                    '/refunds.html',
                    '/bvn-validation.html',
                    '/exchange-rates.html',
                ]
            }
        ],
        displayAllHeaders: true
      },
      markdown: {
        lineNumbers: true
      }
  }