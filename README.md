SeoUrl
============================

Simple ZF2 SEO Url generator. Module converts strings to valid Urls. For example:
`I'm starting a new project – which version of Zend Framework should I use?` will be converted to
`im-starting-a-new-project-which-version-of-zend-framework-should-i-use`. SEO Url Module works also with non latin characters.



Installation
------------
For the installation uses composer [composer](http://getcomposer.org "composer - package manager").
Add this project in your composer.json:


    "require": {
        "cyrkulewski/seo-url": "dev-master"
    }
    

Post Installation
------------
Configuration:
- Add the module of `config/application.config.php` under the array `modules`, insert `SeoUrl`.
- Copy a file named `seourl.global.php.dist` to `config/autoload/` and change name to `seourl.global.php`.
- Modify config to fit your expectations.


Examples
=====================================
Default use of SEO Url
------------
```php
$slug = $this->getServiceLocator()->get('SeoUrl\Slug');
echo $slug->create("I'm starting a new project – which version of Zend Framework should I use?");
```


Advanced use of SEO Url
------------
By default SEO Url use parameters defined in `config/autoload/seourl.global.php`. But one might need to use different setup in different places around the code. In this case one can overwrite default confid parameters.
```php
$slug = $this->getServiceLocator()->get('SeoUrl\Slug');
$slug->setMinLength(10);
$slug->setMaxLength(100);
$slug->setSeparator('_');
$slug->setStringEncoding('UTF-16');
$slug->setForeignChars(array('/я/' => 'ja'));
echo $slug->create("I'm starting a new project – which version of Zend Framework should I use?");
```


Contributors
=====================================

Aleksander Cyrkulewski - cyrkulewski@gmail.com
